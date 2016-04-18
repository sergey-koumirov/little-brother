<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<script src="/js/go.js" charset="utf-8"></script>
<script src="/js/sankey.js"></script>

<script>
    var removeEntity = function(id){
        $.ajax( '<?= Url::to(['analysis/delete-entity','id'=>$model->id ]) ?>?delete_id='+id )
            .done(function(response) {
                $('#entity-'+id).remove();
                myDiagram.model = go.Model.fromJson(response.data);
            })
            .fail(function(response) {
                console.debug( "removeEntity: error", response );
            }); 
    }
    
    $(function() {
        $( "#selector" ).autocomplete({
            source: '<?= Url::to(['analysis/search','id'=>$model->id ]) ?>',
            minLength: 3,
            select: function( event, ui ) {
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
        
        $( "#add_entity" ).click(function(){
            var base_add_url = '<?= Url::to(['analysis/add-entity','id'=>$model->id ]) ?>',
                entityType = $("#selected_type").val(),
                entityId = $("#selected_id").val();
            
            if(!!entityType && !!entityId){
                $.ajax( base_add_url + '?entity_type='+entityType+'&entity_id='+entityId )
                    .done(function(response) {
                        console.debug(response);
                        var m = response.model;
                        $('ul.entities').append('<li id="entity-'+m.id+'">['+m.entity_type+']: '+m.entity.name+' <a href="javascript: removeEntity('+m.id+');">X</a></li>');
                        $("#selected_type").val(null);
                        $("#selected_id").val(null);
                        $("#selector").val(null);

                        myDiagram.model = go.Model.fromJson(response.data);
                    })
                    .fail(function() {
                        console.debug( "error" );
                    });    
            }
        });
        
    });

    $.get( "<?= Url::to(['analysis/data','id'=>$model->id]) ?>", function( data ) {
        myDiagram.model = go.Model.fromJson(data);
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
        <ul class="entities">
            <?php foreach($entities as $entity){ ?>
            <li id="entity-<?= $entity->id ?>">
                [<?= $entity->entity_type ?>]: <?= $entity->entity->name ?>
                <a href="javascript: removeEntity(<?= $entity->id ?>);">X</a>
            </li>
            <?php } ?>
        </ul>
    </div>
    <div class="col-sm-1">
        <button id="add_entity" class="btn btn-primary">+</button>            
    </div>
</div>


<div id="myDiagramDiv" style="xbackground-color: #696969; border: solid 1px black; width: 1200px; height: 400px"></div>

<script>
    init();
</script>