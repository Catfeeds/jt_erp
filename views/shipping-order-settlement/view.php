<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ShippingSettlement */

$this->title = '订单结算: '.$model->id_order;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventorys-view box box-primary">
    <div class="box-header">
        <button type="submit" class="btn btn-primary back" style="margin-bottom: 10px;margin-right: 10px;margin-top: 10px;">返回</button>

    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id_order',
                'lc_number',
                'back_order_total',
                'currency',
                [
                    'attribute' => 'status',
                    'value' => function($model){
                        return \app\models\ShippingOrderSettlement::$status_arr[$model->status];
                    }
                ],
                'created_at',
                'update_at',
            ],
        ]) ?>
    </div>
    <table class="table table-striped table-bordered detail-view">
        <tr><th colspan="6" align="center">明细</th></tr>
        <tr>
            <th></th>
            <th>订单号</th>
            <th>运单号</th>
            <th>回款金额</th>
            <th>COD费用</th>
            <th>实际运费</th>
            <th>其他费用</th>
            <th>货币</th>
            <th>操作人</th>
            <th>创建时间</th>
        </tr>
        <?php foreach($items as $item):?>
            <tr>
                <td><?=++$i?></td>
                <td><?=$item['id_order']?></td>
                <td><?=$item['lc_number']?></td>
                <td><?=$item['back_order_total']?></td>
                <td><?=$item['cod_fee']?></td>
                <td><?=$item['shipping_fee']?></td>
                <td><?=$item['other_fee']?></td>
                <td><?=$item['currency']?></td>
                <td><?= $user_arr[$item['uid']]?></td>
                <td><?= $item['created_at']?></td>
            </tr>
        <?php endforeach;?>
    </table>
    <div></div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . "/js/layer/layer.js", ['depends' => ['app\assets\AppAsset']]); ?>
<script>
    $(".back").on('click',function () {
        window.location.assign('/shipping-order-settlement/index');
    });
</script>
