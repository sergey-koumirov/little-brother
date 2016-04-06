<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>


<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <h4>Workers <?= date('Y-m-d H:i:s') ?> <?= date_default_timezone_get() ?></h4>
        </div>      
        <div class="col-sm-1">
            <a href="<?= Url::to(['workers/run']) ?>" class="btn btn-primary">Run workers</a>
        </div>      

    </div>  
    <div class="row">
        <div class="col-sm-6">
            Characters need to update: <?= $outdatedCharactersCnt ?>
        </div>  
    </div>  
</div>



