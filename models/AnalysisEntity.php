<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\Alliance;
use app\models\Corporation;

class AnalysisEntity extends ActiveRecord{
    
    public static function tableName(){
        return 'analysis_entities';
    }
    
    public function getEntity(){        
        
        if($this->entity_type == 'corp'){
            return $this->hasOne(Corporation::className(), ['corporation_id' => 'entity_id']);
        }elseif($this->entity_type == 'alliance'){
            return $this->hasOne(Alliance::className(), ['alliance_id' => 'entity_id']);
        }else{
            return null;
        }        
        
    }
    
    public function fields(){
        $fields = parent::fields();
        $fields[] = 'entity';

        return $fields;
    }
    
}
