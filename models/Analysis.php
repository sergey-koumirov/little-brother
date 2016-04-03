<?php

namespace app\models;

use yii\db\ActiveRecord;

class Analysis extends ActiveRecord{
    
    public function rules(){
        return [
            ['name', 'required'],
            ['date_from', 'required'],
            ['date_to', 'required'],
        ];
    }   
    
}