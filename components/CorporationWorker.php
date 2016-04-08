<?php

namespace app\components;

use Pheal\Pheal;
use Pheal\Core\Config;
use app\models\Corporation;
use app\models\Alliance;

class CorporationWorker{

    public function perform(){
        echo "CorporationWorker.perform\n";
        
        Alliance::updateAllianceList();
        
        Config::getInstance()->cache = new \Pheal\Cache\FileStorage('/tmp/');
        $pheal = new Pheal('', '', "corp");
        do {
            foreach(Corporation::outdated10() as $c){
                $response = $pheal->CorporationSheet(["corporationID" => $c['corporation_id']]);
                echo($response->corporationName."\n");
                
                $record = Corporation::findOne($c['corporation_id']);
                if($record==null){
                    $record = new Corporation;
                    $record->corporation_id = $c['corporation_id'];
                    $record->name = $response->corporationName;
                    $record->save();
                }
                $record->updateEmployment(); 
                
                $record->updated_at = date('Y-m-d H:i:s');
                $record->save();                
                usleep(100000);            
            }
        }while(Corporation::outdatedCnt()>0); 
        echo "CorporationWorker.done\n";        
    }
    
    public function setUp(){
    }
    
    public function tearDown(){
    }
}