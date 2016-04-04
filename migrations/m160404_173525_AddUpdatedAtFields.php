<?php

use yii\db\Migration;

class m160404_173525_AddUpdatedAtFields extends Migration
{
    public function up(){
        $this->addColumn('characters', 'updated_at', $this->dateTime() );
        $this->addColumn('corporations', 'updated_at', $this->dateTime() );
    }

    public function down(){
        $this->dropColumn('characters', 'updated_at');
        $this->dropColumn('corporations', 'updated_at');
    }


}
