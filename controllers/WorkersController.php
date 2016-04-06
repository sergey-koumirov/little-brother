<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

use yii\web\Controller;

use Pheal\Pheal;
use Pheal\Core\Config;


class WorkersController extends Controller{
    //put your code here
    public function actionIndex(){
        $query = (new \yii\db\Query())->from('characters')->where('hour(timediff(now(), updated_at))>48 or updated_at is null');
        $outdatedCharactersCnt = $query->count();
        return $this->render('index', ['outdatedCharactersCnt'=>$outdatedCharactersCnt]);
    }
    
    public function actionRun(){
        \Yii::$app->resque->createJob('character', 'CharacterWorker', $args = []);
        return $this->redirect(['workers/index']);
    }
}
