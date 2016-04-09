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
    
    
    public function fromPairs(){
        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand(
            "select concat(ifnull(concat('[', af.name, '] '),''), cf.name) as name, count(1) as cnt
              from character_history ch
                     left join corporations cf on cf.corporation_id = ch.corporation_from_id
                     left join corporation_history rf on rf.corporation_id = cf.corporation_id 
                                                     and (rf.date_from is null or rf.date_from <= :start_date)
                                                     and (rf.date_to is null or rf.date_to >= :start_date)
                     left join alliances af on af.alliance_id = rf.alliance_id
              where ch.corporation_id = :corporation_id
                and ch.date_from >= :start_date
                and ch.date_from <= :end_date
              group by af.name, cf.name, ch.corporation_from_id, ch.corporation_id  
              order by count(1) desc", 
            [':corporation_id' => 877122797, ':start_date' => '2016-01-01', ':end_date' => '2016-12-31']
        );

        $result = $command->queryAll();
        return $result;
    }
    
    public function toPairs(){
        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand(
            "select concat(ifnull(concat('[', at.name, '] '),''), ct.name) as name, count(1) as cnt
                from character_history ch
                       left join corporations ct on ct.corporation_id = ch.corporation_to_id
                       left join corporation_history rt on rt.corporation_id = ct.corporation_id 
                                                       and (rt.date_from is null or rt.date_from <= :end_date)
                                                       and (rt.date_to is null or rt.date_to >= :end_date)
                       left join alliances at on at.alliance_id = rt.alliance_id

                where ch.corporation_id = :corporation_id
                  and ch.date_to >= :start_date
                  and ch.date_to <= :end_date
                group by at.name, ct.name, ch.corporation_from_id, ch.corporation_id  
                order by count(1) desc", 
            [':corporation_id' => 877122797, ':start_date' => '2016-01-01', ':end_date' => '2016-12-31']
        );

        $result = $command->queryAll();
        return $result;
    }
    
    public function analysisData(){
        $from = $this->fromPairs();
        $to = $this->toPairs();
        
        $nodes = array();
        foreach($from as $el){
            $nodes[] = ["key"=>'F:'.$el['name'], "text"=>$el['cnt'].' '.$el['name'], "color"=>"#60A060"];
        }
        $nodes[] = ["key"=>'Squadron', "text"=>'Squadron', "color"=>"#6060A0"];
        foreach($to as $el){
            $nodes[] = ["key"=>'T:'.$el['name'], "text"=>$el['cnt'].' '.$el['name'], "color"=>"#A06060"];
        }
        
        $links = array();
        foreach($from as $el){
            $links[] = ["from" => 'F:'.$el['name'], "to" => 'Squadron', "width" =>$el['cnt']];
        }
        foreach($to as $el){
            $links[] = ["from" => 'Squadron', "to" => 'T:'.$el['name'], "width" =>$el['cnt']];
        }
        
        return [
            'class' => 'go.GraphLinksModel',
            'nodeDataArray' => $nodes,
            "linkDataArray" => $links            
        ];
        
    }

    
}
