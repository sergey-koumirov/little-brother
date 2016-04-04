<?php

namespace app\models;

use yii\db\ActiveRecord;

class Character extends ActiveRecord{
    public static function tableName(){
        return 'characters';
    }
}