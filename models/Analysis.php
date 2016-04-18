<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\AnalysisEntity;

class Analysis extends ActiveRecord{
    
    public function rules(){
        return [
            ['name', 'required'],
            ['date_from', 'required'],
            ['date_to', 'required'],
        ];
    }
    
    
    public function fromPairs($ids){
        if(count($ids) == 0){ return []; }

        $jids = implode($ids);

        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand(
            "select c.name as center_name, concat(ifnull(concat('[', af.name, '] '),''), cf.name) as name, count(1) as cnt
              from character_history ch
                      left join corporations c on c.corporation_id = ch.corporation_id
                      left join corporations cf on cf.corporation_id = ch.corporation_from_id
                      left join corporation_history rf on rf.corporation_id = cf.corporation_id 
                                                      and (rf.date_from is null or rf.date_from <= :start_date)
                                                      and (rf.date_to is null or rf.date_to >= :start_date)
                      left join alliances af on af.alliance_id = rf.alliance_id
              where ch.corporation_id in (".$jids.")
                and ch.date_from >= :start_date
                and ch.date_from <= :end_date
              group by c.name, af.name, cf.name, ch.corporation_from_id, ch.corporation_id  
              order by count(1) desc", 
            [':start_date' => $this->date_from, ':end_date' => $this->date_to]
        );

        $result = $command->queryAll();
        return $result;
    }
    
    public function toPairs($ids){
        if(count($ids) == 0){ return []; }

        $jids = implode($ids);

        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand(
            "select c.name as center_name, concat(ifnull(concat('[', at.name, '] '),''), ct.name) as name, count(1) as cnt
                from character_history ch
                        left join corporations c on c.corporation_id = ch.corporation_id
                        left join corporations ct on ct.corporation_id = ch.corporation_to_id
                        left join corporation_history rt on rt.corporation_id = ct.corporation_id 
                                                        and (rt.date_from is null or rt.date_from <= :end_date)
                                                        and (rt.date_to is null or rt.date_to >= :end_date)
                        left join alliances at on at.alliance_id = rt.alliance_id

                where ch.corporation_id in (".$jids.")
                  and ch.date_to >= :start_date
                  and ch.date_to <= :end_date
                group by c.name, at.name, ct.name, ch.corporation_from_id, ch.corporation_id  
                order by count(1) desc", 
            [':start_date' => $this->date_from, ':end_date' => $this->date_to]
        );

        $result = $command->queryAll();
        return $result;
    }
    
    public function analysisData(){
        $info = $this->corporationInfo();

        $from = $this->fromPairs( $info['ids'] );
        $to = $this->toPairs( $info['ids'] );
        
        $nodes = array();
        foreach($from as $el){
            $nodes[] = ["key"=>'F:'.$el['name'], "text"=>$el['cnt'].' '.$el['name'], "color"=>"#60A060"];
        }
        
        foreach($info['names'] as $name){
            $nodes[] = ["key"=>$name, "text"=>$name, "color"=>"#6060A0"];
        }
        
        foreach($to as $el){
            $nodes[] = ["key"=>'T:'.$el['name'], "text"=>$el['cnt'].' '.$el['name'], "color"=>"#A06060"];
        }
        
        $links = array();
        foreach($from as $el){
            $links[] = ["from" => 'F:'.$el['name'], "to" => $el['center_name'], "width" =>$el['cnt']];
        }
        foreach($to as $el){
            $links[] = ["from" => $el['center_name'], "to" => 'T:'.$el['name'], "width" =>$el['cnt']];
        }
        
        return [
            'class' => 'go.GraphLinksModel',
            'nodeDataArray' => $nodes,
            "linkDataArray" => $links            
        ];
        
    }

    public function corporationInfo(){
        $corps = AnalysisEntity::find()->where(['analysis_id' => $this->id, 'entity_type' => 'corp'])->all();
        $names = array();
        $ids = array();
        foreach($corps as $corp){
            $names[] = $corp->entity->name;  
            $ids[] = $corp->entity_id;
        }
        return ['names' => $names, 'ids' => $ids];
    }

    
}
