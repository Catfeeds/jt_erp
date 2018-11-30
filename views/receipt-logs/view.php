<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ReceiptLogs */

$this->title = '收货入库单：'.$model->id;
$this->params['breadcrumbs'][] = ['label' => '收货入库单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-logs-view box box-primary">
    <div class="box-header">

    </div>
    <div class="box-body table-responsive">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'track_number',
                'create_date',
                [
                    'attribute' => 'create_uid',
                    'value'     => function($model) {
                        $user = new \app\models\User();
                        return $user->getUsername($model->create_uid);
                    }
                ],
                'comment:ntext',
                [
                    'attribute' => 'status',
                    'value' => function($model){
                        return $model->status_array[$model->status];
                    }
                ],
            ],
        ]) ?>
        <table class="table table-striped table-bordered detail-view">
            <tr>
                <th>采购单号</th>
                <th>SKU</th>
                <th>应收</th>
                <th>实收</th>
                <th>库位</th>
                <th>异常</th>
            </tr>
            <?php
            foreach($items as $item):
            ?>
            <tr>
                <td><?=$item->order_number?></td>
                <td><?=$item->sku?></td>
                <td><?=$item->buy_qty?></td>
                <td><?=$item->get_qty?></td>
                <td><?=$item->location_code?></td>
                <td><?=$item->status_array[$item->warning_status]?></td>
            </tr>
            <?php
            endforeach;
            ?>
        </table>
        <div class="box-footer">
        <button class="btn-success btn" type="button" onclick="updateStock(<?=$model->id?>)">确认上架</button>
        </div>
    </div>
</div>
<script>
    function updateStock(id) {
        $.get('/receipt-logs/update-stock', {id:id}, function(data){
            if(200==data){
                alert('成功');
                location.reload();
            }else{
                alert(data);
            }
        });
    }
</script>
