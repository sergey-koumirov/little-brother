<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\CorporationHistory;
               
class Corporation extends ActiveRecord{
    public static function tableName(){
        return 'corporations';
    }
    
    
    public static function outdatedCnt(){
        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand(
            'select count(distinct ch.corporation_id) as cnt '.
            '  from character_history ch '.
            '         left join corporations c on c.corporation_id=ch.corporation_id '.
            '  where hour(timediff(now(), c.updated_at))>48 or c.updated_at is null'
        );
        $result = $command->queryAll();
        return $result[0]['cnt'];
    }
    
    public static function outdated10(){
        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand(
            'select distinct ch.corporation_id '.
            '  from character_history ch '.
            '         left join corporations c on c.corporation_id=ch.corporation_id '.
            '  where hour(timediff(now(), c.updated_at))>48 or c.updated_at is null '.
            '  order by ch.corporation_id desc '.    
            '  limit 10'
        );
        return $command->queryAll();
    }
    
    public function updateEmployment(){
        $ch = curl_init();
        $timeout = 5;
        $url = "https://gate.eveonline.com/Corporation/".curl_escape($ch,$this->name)."/CorporationInfoPanel?tab=AllianceHistory";
        //echo $url."\n";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        if(curl_errno($ch) || $http_code==500 ){ 
            //echo '  Curl error: '.$http_code."\n"; 
            //echo $data; 
        }else{
            //echo 'Curl success: '.$http_code."\n"; 
            $doc = new \DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($data);               
            $prevs = $this->parsePrevAlliances($doc);
            $curr = $this->parseCurrentAlliance($doc);
            if($curr!=null){
                //$prevs[] = $curr;
                array_unshift($prevs, $curr);
            } 
            $this->createHistoryRecords($prevs);
        }
        curl_close($ch);
    }
    
    private function createHistoryRecords($raws){
        $cnt = count($raws);
        for($i=0; $i<$cnt; $i++){
        //foreach($raws as $raw){
            $raw = $raws[$i];            
        
            $alliance_to = ($i<=0 ? null : Alliance::byName($raws[$i-1][0], $raws[$i-1][3]) );
            $alliance = Alliance::byName($raw[0], $raw[3]);
            $alliance_from = ($i>=$cnt-1 ? null : Alliance::byName($raws[$i+1][0], $raws[$i+1][3]) );
            
            if($alliance != null){
                echo "    ".$raw[0].": ".$raw[1]." - ".$raw[2]." ... ".$raw[3]."\n";
            
                $hrecord = CorporationHistory::find()->where([
                    'corporation_id' => $this->corporation_id,
                    'alliance_id' => $alliance->alliance_id,
                    'date_from' => $raw[1]
                ])->one();
                if($hrecord==null){
                    $hrecord = new CorporationHistory;
                    $hrecord->corporation_id = $this->corporation_id;
                    $hrecord->alliance_from_id = ($alliance_from==null ? null : $alliance_from->alliance_id);
                    $hrecord->alliance_id = $alliance->alliance_id;
                    $hrecord->alliance_to_id = ($alliance_to==null ? null : $alliance_to->alliance_id);
                    $hrecord->date_from = ($raw[1] == "Unknown" ? "2000-01-01" : $raw[1]);
                    $hrecord->date_to = $raw[2];
                    $hrecord->save();
                }elseif($hrecord->date_to != $raw[2]){
                    $hrecord->date_to = $raw[2];
                    $hrecord->save();
                }    
            }else{
                echo "    ".$raw[0].": ".$raw[1]." - ".$raw[2]." ... ".$raw[3]." ?\n";            
            }
        }
    }
    
    
    private function parsePrevAlliances($doc){
        $xpath = new \DOMXPath($doc);
        $expr = "/html/body/div[span[text()='PREVIOUS ALLIANCE(S)']]/div";
        $result = $xpath->query($expr);
        if($result===false){
            echo "wrong xml\n";                
        }else{          
            $prevs = array();
            foreach($result as $node){
                $parts = explode("\n",trim($node->nodeValue));
                
                $is_closed = (strpos($parts[0],' (Closed)') !== false);
                
                $str_name = preg_replace('/\[.*\]$/', '', trim($parts[0]));                

                $dates_str = str_replace('from ','',$parts[1]);
                $dates = explode(" to ", $dates_str);

                $prevs[] = [$str_name, trim($dates[0]), substr($dates[1], 0, -1), $is_closed];
            }
            return $prevs;            
        }
        return [];
    }
    
    private function parseCurrentAlliance($doc){
        $xpath = new \DOMXPath($doc);
        $expr = "/html/body/div[span[text()='CURRENT ALLIANCE']]/div";
        $result = $xpath->query($expr);
        if($result===false){
            echo "wrong xml\n";                
        }else{            
            foreach($result as $node){
                if(strpos($node->nodeValue,'is not member of an alliance at the moment')){
                    return null;
                }else{     
                    //echo $node->nodeValue;
                    // from 2008.05.11 23:43 to this day.
                             
                    $parts = explode("\n", trim($node->nodeValue));
                    
                    //print_r($parts);
                    
                    $allianceName = preg_replace('/\[.*\]$/', '', trim($parts[0])); 
                    $is_closed = (strpos($parts[0],' (Closed)') !== false);
                    
                    $dates_str = str_replace('from ','',$parts[1]);
                    $dates = explode(" to ", $dates_str);
                    
                    return [$allianceName,  trim($dates[0]), null, $is_closed];
                }
            }
        }
        return null;
    }
    
}