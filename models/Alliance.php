<?php

namespace app\models;

use yii\db\ActiveRecord;
use Pheal\Pheal;
use Pheal\Core\Config;

class Alliance extends ActiveRecord{
    public static function tableName(){
        return 'alliances';
    }
    
    public static function updateAllianceList(){
        $datetime1 = new \DateTime();
        $datetime2 = new \DateTime(Settings::byName('AlliancesUpdatedAt'));
        $interval = $datetime1->diff($datetime2);
        if( $interval->days > 6 ){
            Config::getInstance()->cache = new \Pheal\Cache\FileStorage('/tmp/');
            $pheal = new Pheal('', '', "eve");
            try {
                $response = $pheal->AllianceList();
                echo "AllianceList\n";
                foreach($response->alliances as $alliance){
                    echo "  ".$alliance->name."\n";
                    $model = Alliance::findOne($alliance->allianceID);
                    if($model==null){
                        $model = new Alliance;
                        $model->alliance_id = $alliance->allianceID;
                        $model->name = $alliance->name;
                        $model->save();
                    }
                }                
                Settings::setValue('AlliancesUpdatedAt', date('Y-m-d H:i:s'));                
            } catch (\Pheal\Exceptions\PhealException $e) {
                echo("Error: ".$e->getCode()."\n");
            }
        }
    }
    
    public static function byName($name, $is_closed){
        $alliance = Alliance::find()->where(['name' => $name])->one();
            
        if($alliance==null && $is_closed){
            $min_id = Alliance::find()->min('alliance_id');                 
            if($min_id > 99000000 || $min_id==null){
                $min_id = 98000000; 
            }else{
                $min_id = $min_id - 1; 
            }                
            $alliance = new Alliance;
            $alliance->name = $name;
            $alliance->alliance_id = $min_id;
            $alliance->save();
        }
        
        return $alliance;
    }
    
}