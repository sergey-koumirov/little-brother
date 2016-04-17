<?php

use yii\db\Migration;

class m160417_185107_AddAnalysisId extends Migration
{
    public function up(){
        $this->addColumn('analysis_entities', 'analysis_id', $this->integer() );
    }

    public function down(){
        $this->dropColumn('analysis_entities', 'analysis_id');
    }
}
