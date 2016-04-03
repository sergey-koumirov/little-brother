<?php

use yii\db\Migration;

class m160403_113800_AddDatesToAnalysis extends Migration
{
    public function up(){
        $this->addColumn('analysis', 'date_from', $this->date());
        $this->addColumn('analysis', 'date_to', $this->date());
    }

    public function down(){
        $this->dropColumn('analysis', 'date_from');
        $this->dropColumn('analysis', 'date_to');
    }
}
