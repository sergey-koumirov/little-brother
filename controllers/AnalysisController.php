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
    
    public function actionNew(){
        $model = new Analysis();
        return $this->render('new', ['model'=>$model]);
    }
    
    public function actionCreate(){
        $model = new Analysis();
        $model->load( \Yii::$app->request->post() );
        if ($model->validate() && $model->save()) {
            return $this->redirect( ['analysis/view', 'id' => $model->id ] );
        } else {
            return $this->render('new', ['model'=>$model]);
        }
    }
    
    public function actionView($id){
        
        \Yii::$app->resque->createJob('character', 'CharacterWorker', $args = ['id' => $id]);
        
        $model = Analysis::findOne($id);
        return $this->render('view', ['model'=>$model]);
    }
    
    public function actionUpdate($id){
        $model = Analysis::findOne($id);
        $model->load( \Yii::$app->request->post() );
        if ($model->validate() && $model->save()) {
            return $this->redirect( ['analysis/view', 'id' => $model->id ] );
        } else {
            return $this->render('view', ['model'=>$model]);
        }
    }
    
    public function actionDelete($id){
        $model = Analysis::findOne($id);
        $model->delete();
        return $this->redirect( ['analysis/index'] );
    }
    
    public function actionData(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $data = [
            'class' => 'go.GraphLinksModel',
            'nodeDataArray' => [
                ["key"=>'A', "text"=>"A (1)", "color"=>"#408040"],
                ["key"=>'B', "text"=>"B (1)", "color"=>"#408040"],
                ["key"=>'C', "text"=>"C (1)", "color"=>"#408040"],
                ["key"=>'D', "text"=>"D (1)", "color"=>"#408040"],
                ["key"=>'E', "text"=>"E (1)", "color"=>"#408040"],
                
                ["key"=>'X', "text"=>"XIX", "color"=>"#808040"],
                ["key"=>'S', "text"=>"Squadron", "color"=>"#808040"],
                
                ["key"=>'F', "text"=>"F (1)", "color"=>"#804040"],
                ["key"=>'G', "text"=>"G (1)", "color"=>"#804040"],
                ["key"=>'H', "text"=>"H (2)", "color"=>"#804040"],
            ],
            "linkDataArray" => [
                ["from" => "A", "to" => "X", "width" =>1],
                ["from" => "B", "to" => "X", "width" =>1],
                ["from" => "C", "to" => "X", "width" =>1],
                ["from" => "D", "to" => "X", "width" =>1],
                ["from" => "D", "to" => "S", "width" =>1],
                ["from" => "E", "to" => "S", "width" =>1],
                
                ["from" => "X", "to" => "F", "width" =>1],
                ["from" => "X", "to" => "G", "width" =>1],
                ["from" => "X", "to" => "H", "width" =>1],
                
                ["from" => "S", "to" => "H", "width" =>1],
                ["from" => "S", "to" => "F", "width" =>1],
                
            ]
            
        ];
        
        return $data;
    }
}

