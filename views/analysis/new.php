<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>


<div class="row">
    <div class="col-sm-6">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'action' => Url::to(['analysis/create']),
            'layout' => 'horizontal'
        ]) ?>
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'date_from') ?>
            <?= $form->field($model, 'date_to') ?>
            <div class="form-group">
                <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end() ?>
    </div>
</div>

