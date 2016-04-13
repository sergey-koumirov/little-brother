<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>

<script>
    $(function() {
        $( "#selector" ).autocomplete({
            source: "/analysis/search",
            minLength: 3,
            select: function( event, ui ) {
                console.debug(ui);
                $("#selected_id").val(ui.item.id);
                $("#selected_type").val(ui.item.type);
                $("#selector").val("[" + ui.item.type + ']: ' + ui.item.name);
                return false;
            }
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
              .append( "<a>[" + item.type + ']: ' + item.name + "</a>" )
              .appendTo( ul );
        };
    });
</script>


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
    <div class="col-sm-5">
        <input id="selected_id" type="hidden">
        <input id="selected_type" type="hidden">
        <input id="selector" class="form-control" type="text" placeholder="Type corp or alliance name">
    </div>
    <div class="col-sm-1">
        <button class="btn btn-primary">+</button>
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