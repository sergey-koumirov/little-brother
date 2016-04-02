<?php
use yii\helpers\Html;
?>


<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <h4>Analysises</h3>
            <?= Html::a('add', ['/analysis/new'], ['class'=>'btn btn-primary btn-xs']) ?>
        </div>      
    </div>  
    <div class="row">
        <div class="col-sm-6">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
                
                <?php foreach ($collection as $record): ?>
                    <tr>
                        <td><?= $record->id ?></td>
                        <td><?= Html::encode($record->name) ?></td>
                    </tr>
                <?php endforeach; ?>
                
            </table>
        </div>  
    </div>  
</div>



