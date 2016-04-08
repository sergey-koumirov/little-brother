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
use app\models\Character;
use app\models\Corporation;


class WorkersController extends Controller{
    //put your code here
    public function actionIndex(){
        return $this->render('index', [
            'outdatedCharactersCnt'=>Character::outdatedCnt(),
            'outdatedCorporationsCnt'=>Corporation::outdatedCnt()
        ]);
    }
    
    public function actionRun(){
        \Yii::$app->resque->createJob('api', 'CharacterWorker', $args = []);
        \Yii::$app->resque->createJob('api', 'CorporationWorker', $args = []);
        return $this->redirect(['workers/index']);
    }
}
