<?php
use yii\helpers\Html;
?>


<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <h4>Analysises</h3>
            <?= Html::a('new', ['/analysis/new'], ['class'=>'btn btn-primary btn-xs']) ?>
        </div>      
    </div>  
    <div class="row">
        <div class="col-sm-6">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>From</th>
                    <th>To</th>
                    <th></th>
                </tr>
                
                <?php foreach ($collection as $record): ?>
                    <tr>
                        <td><?= Html::a($record->id, ['analysis/view', 'id' => $record->id]) ?></td>
                        <td><?= Html::a(Html::encode($record->name), ['analysis/view', 'id' => $record->id]) ?></td>
                        <td><?= Html::a(Html::encode($record->date_from), ['analysis/view', 'id' => $record->id]) ?></td>
                        <td><?= Html::a(Html::encode($record->date_to), ['analysis/view', 'id' => $record->id]) ?></td>
                        <td>
                            <?= Html::a('x', ['analysis/delete', 'id' => $record->id],
                                    [
                                        'class' => 'btn btn-default btn-xs',
                                        'data' => [
                                            'confirm' => "Are you sure you want to delete record?"
                                        ]
                                    ]
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
            </table>
        </div>  
    </div>  
</div>



