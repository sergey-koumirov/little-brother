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
    
}