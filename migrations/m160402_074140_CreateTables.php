<?php

use yii\db\Migration;

class m160402_074140_CreateTables extends Migration
{
    public function up(){
        $this->createTable('characters', [
            'character_id' => $this->integer(),
            'name' => $this->string(),
            'PRIMARY KEY(character_id)'
        ]);
        
        $this->createTable('character_history', [
            'id' => $this->primaryKey(),
            'date_from' => $this->datetime(),
            'date_to' => $this->datetime(),
            'corporation_from_id' => $this->integer(),
            'corporation_id' => $this->integer(),
            'corporation_to_id' => $this->integer()
        ]);
        $this->createIndex('idx-character_history-date_from', 'character_history', 'date_from');
        $this->createIndex('idx-character_history-date_to', 'character_history', 'date_to');
        $this->createIndex('idx-character_history-corporation_from_id', 'character_history', 'corporation_from_id');
        $this->createIndex('idx-character_history-corporation_id', 'character_history', 'corporation_id');
        $this->createIndex('idx-character_history-corporation_to_id', 'character_history', 'corporation_to_id');
        
        $this->createTable('corporations', [
            'corporation_id' => $this->integer(),
            'name' => $this->string(),
            'PRIMARY KEY(corporation_id)'
        ]);
        
        $this->createTable('corporation_history', [
            'id' => $this->primaryKey(),
            'date_from' => $this->datetime(),
            'date_to' => $this->datetime(),
            'alliance_from_id' => $this->integer(),
            'alliance_id' => $this->integer(),
            'alliance_to_id' => $this->integer()
        ]);
        $this->createIndex('idx-corporation_history-date_from', 'corporation_history', 'date_from');
        $this->createIndex('idx-corporation_history-date_to', 'corporation_history', 'date_to');
        $this->createIndex('idx-corporation_history-alliance_from_id', 'corporation_history', 'alliance_from_id');
        $this->createIndex('idx-corporation_history-alliance_id', 'corporation_history', 'alliance_id');
        $this->createIndex('idx-corporation_history-alliance_to_id', 'corporation_history', 'alliance_to_id');
        
        $this->createTable('alliances', [
            'alliance_id' => $this->integer(),
            'coalition_id' => $this->integer(),
            'name' => $this->string(),
            'PRIMARY KEY(alliance_id)'
        ]);
        
        $this->createTable('analysis', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);
        
        $this->createTable('analysis_entities', [
            'id' => $this->primaryKey(),
            'entity_type' => $this->string(),
            'entity_id' => $this->integer(),
            'entity_role' => $this->string()            
        ]);
        
    }

    public function down(){
        $this->dropTable('characters');
        $this->dropTable('character_history');
        $this->dropTable('corporations');
        $this->dropTable('corporation_history');
        $this->dropTable('alliances');
        $this->dropTable('analysis');
        $this->dropTable('analysis_entities');
    }
}
