<?php

namespace app\models;

use yii\db\ActiveRecord;

class Settings extends ActiveRecord{
    
    public static function byName($name){
        $last = Settings::find()->where(['name'=>$name])->one();
        if($last == null){
            $last = new Settings;
            $last->name = $name;
            $last->save();
        }
        
        $value = $last->value;
        
        if($name == 'AlliancesUpdatedAt' && $value==null){
            $value = '2000-01-01';
        }
        return $value;
    }
    
    public static function setValue($name,$value){
        $last = Settings::find()->where(['name'=>$name])->one();
        if($last == null){
            $last = new Settings;
            $last->name = $name;            
        }
        $last->value = $value;        
        $last->save();
    }
    
}