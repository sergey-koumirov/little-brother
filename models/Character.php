<?php

namespace app\models;

use yii\db\ActiveRecord;

class Character extends ActiveRecord{
    public static function tableName(){
        return 'characters';
    }
    
    public static function outdatedCnt(){
        $query = (new \yii\db\Query())->from('characters')->where('hour(timediff(now(), updated_at))>48 or updated_at is null');
        return $query->count();
    }
    
    public function updateEmployment($character_id, $employmentHistory ){
        $cnt = count($employmentHistory);
        for($i=0; $i<$cnt; $i++){
            $fromId = ($i+1<$cnt ? $employmentHistory[$i+1]->corporationID : null);
            $fromDate = $employmentHistory[$i]->startDate;
            $currId = $employmentHistory[$i]->corporationID;
            $toDate = ($i>0 ? $employmentHistory[$i-1]->startDate : null);
            $toId   = ($i>0 ? $employmentHistory[$i-1]->corporationID : null);
            
            $model = CharacterHistory::find()->where(['corporation_id' => $currId, 'date_from' => $fromDate])->one();
            if($model==null){
                $model = new CharacterHistory;
                $model->character_id = $character_id;
                $model->date_from = $fromDate;
                $model->date_to = $toDate;
                $model->corporation_from_id = $fromId;
                $model->corporation_id = $currId;
                $model->corporation_to_id = $toId;
                $model->save();
            }elseif($model->date_to != $toDate){
                $model->date_to = $toDate;
                $model->corporation_to_id = $toId;
                $model->save();
            }
        }
        foreach($employmentHistory as $corpRec){
            echo("  ".$corpRec->startDate." ".$corpRec->corporationName." [".$corpRec->corporationID."]\n");                                
        }
        
    }
}