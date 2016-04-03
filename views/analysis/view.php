<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>



<div class="row">
    <div class="col-sm-6">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'action' => Url::to(['analysis/update','id'=>$model->id]),
        ]) ?>
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'date_from') ?>
            <?= $form->field($model, 'date_to') ?>
            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end() ?>
    </div>
</div>

<script src="/js/go.js" charset="utf-8"></script>
<script src="/js/sankey.js"></script>
<script>
    jQuery(document).ready(function(){
        jQuery.get( "<?= Url::to(['analysis/data','id'=>$model->id]) ?>", function( data ) {
            myDiagram.model = go.Model.fromJson(data);
        });
    });
</script>


<div id="myDiagramDiv" style="xbackground-color: #696969; border: solid 1px black; width: 90%; height: 400px"></div>

<script>
    init();
</script>