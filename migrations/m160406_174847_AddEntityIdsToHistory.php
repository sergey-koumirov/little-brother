<?php

use yii\db\Migration;

class m160406_174847_AddEntityIdsToHistory extends Migration
{
    public function up(){
        $this->addColumn('character_history', 'character_id', $this->integer() );
        $this->addColumn('corporation_history', 'corporation_id', $this->integer() );
        
        $this->createIndex('idx-character_history-character_id', 'character_history', 'character_id');
        $this->createIndex('idx-corporation_history-corporation_id', 'corporation_history', 'corporation_id');
        
    }

    public function down(){
        $this->dropColumn('character_history', 'character_id');
        $this->dropColumn('corporation_history', 'corporation_id');
    }
}
