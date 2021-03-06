<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Alliance;
?>


<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <h4>Workers <?= date('Y-m-d H:i:s') ?> <?= Alliance::updateAllianceList() ?></h4>
        </div>      
        <div class="col-sm-2">
            <a href="<?= Url::to(['workers/run-character-worker']) ?>" class="btn btn-primary">Run Character worker</a>
        </div>
        <div class="col-sm-2">
            <a href="<?= Url::to(['workers/run-corporation-worker']) ?>" class="btn btn-primary">Run Corporation worker</a>
        </div>

    </div>  
    <div class="row">
        <div class="col-sm-6">
            Characters need to update: <?= $outdatedCharactersCnt ?>
        </div>  
    </div>  
    <div class="row">
        <div class="col-sm-6">
            Corporations need to update: <?= $outdatedCorporationsCnt ?>
        </div>  
    </div>  
</div>



