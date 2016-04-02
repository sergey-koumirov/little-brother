<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Analysis;

class AnalysisController extends Controller
{
    public function actionIndex(){
        
        $collection = Analysis::find()->orderBy('id desc')->all();
        
        return $this->render('index', ['collection'=>$collection]);
    }
    
    public function actionAdd(){
        return $this->render('add');
    }
}

