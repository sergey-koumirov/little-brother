<?php

use yii\db\Migration;

class m160407_163231_AddSettingsTable extends Migration
{
    public function up(){
        $this->createTable('settings', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'value' => $this->string(),
        ]);

    }

    public function down(){
        $this->dropTable('settings');
    }

}
