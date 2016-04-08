<?php

use yii\db\Migration;

class m160407_180655_AddSkipHistoryToCorporation extends Migration
{
    public function up(){
        $this->addColumn('corporations', 'skip_history', $this->boolean() );
    }

    public function down(){
        $this->dropColumn('corporations', 'skip_history');
    }

}
