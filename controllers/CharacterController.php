<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Character;

class CharacterController extends Controller
{
    public function actionAddBatch(){
        $request = \Yii::$app->request;
        $ids = $request->post('ids');
        
        $lines = explode("\n", $ids);
        
        foreach ($lines as $line) {
            if(ctype_digit( trim($line) )){
                $modelId = intval($line);
                $model = Character::findOne($modelId);
                if($model==null){
                    $model = new Character();
                    $model->character_id = intval($line);
                    $model->save();
                }
            }
        } 
        
        return $this->render('add-batch',[ 'ids' => $ids ]);
    }
    

}

