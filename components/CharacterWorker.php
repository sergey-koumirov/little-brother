<?php

namespace app\components;

use Pheal\Pheal;
use Pheal\Core\Config;
use app\models\Character;

class CharacterWorker{

    public function setUp(){
    }

    public function perform(){
        echo "CharacterWorker.perform\n";
        Config::getInstance()->cache = new \Pheal\Cache\FileStorage('/tmp/');
        $pheal = new Pheal('', '', "eve");
        do {
            $outdatedCharactersCnt = Character::outdatedCnt();
            if( $outdatedCharactersCnt>0 ){
                foreach( 
                    $chrs = Character::find()
                                ->where('hour(timediff(now(), updated_at))>48 or updated_at is null')
                                ->batch(10) as $batch
                ){
                    foreach( $batch as $record ){
                        
                        try {
                            $response = $pheal->CharacterInfo(["characterID" => $record->character_id]);
                            echo($response->characterName."\n");
                            $record->updateEmployment($record->character_id, $response->employmentHistory); 
                            
                            $record->name = $response->characterName;
                            $record->updated_at = date('Y-m-d H:i:s');
                            $record->save();
                            
                            usleep(100000);        
                        } catch (\Pheal\Exceptions\PhealException $e) {
                            if($e->getCode() == 400 || $e->getCode() == 500){
                                $record->delete();
                                echo("  deleted\n");                            
                            }
                            usleep(100000);
                        }
                        
                    }
                }                    
            }
        }while($outdatedCharactersCnt>0); 
        echo "CharacterWorker.done\n";
    }

    public function tearDown(){
    }
}

