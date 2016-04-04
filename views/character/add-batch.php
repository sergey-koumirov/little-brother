<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <h4>Add Character IDs</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            
            <form action="<?= Url::to(['character/add-batch']) ?>" method="POST">
                
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                
                <textarea rows="20" name="ids"><?= $ids ?></textarea>
                
                <input type="submit" class="btn btn-primary" value="Add">
                
            </form>
            
        </div>
    </div>
</div> 