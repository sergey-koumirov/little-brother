<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Analysis;
use app\models\Alliance;
use app\models\Corporation;
use app\models\AnalysisEntity;

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
        $model = Analysis::findOne($id);
        
        $entities = AnalysisEntity::find()->all();
        
        return $this->render('view', ['model'=>$model, 'entities' => $entities]);
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
    
    public function actionData($id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = Analysis::findOne($id);
        $data = $model->analysisData(); 
        return $data;
    }
    
    public function actionSearch(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $request = \Yii::$app->request;
        $term = '%'.$request->get('term').'%';
        
        $result = array();
        $alliances = Alliance::find()->where("name like :name",['name'=>$term])->orderBy('name')->limit(5)->all();
        foreach($alliances as $alliance){
            $result[] = ['name'=>$alliance->name, 'type'=>'alliance', 'id'=>$alliance->alliance_id];
        }
        
        $corporations = Corporation::find()->where("name like :name",['name'=>$term])->orderBy('name')->limit(5)->all();
        foreach($corporations as $corporation){
            $result[] = ['name'=>$corporation->name, 'type'=>'corp', 'id'=>$corporation->corporation_id];
        }
        
        return $result;
    }
    
    public function actionAddEntity($id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $request = \Yii::$app->request;
        
        $model = new AnalysisEntity;
        $model->analysis_id = $id;
        $model->entity_type = $request->get('entity_type');
        $model->entity_id = $request->get('entity_id');
        $model->entity_role = 'center';
        $model->save();
        
        return ['message'=>'ok', 'model'=>$model ];
    }
    
    public function actionDeleteEntity($id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->request;
        $model = AnalysisEntity::find()->where(['analysis_id'=>$id, 'id'=> $request->get('delete_id')])->one();
        $model->delete();
        return ['message'=>'ok'];
    }
}

