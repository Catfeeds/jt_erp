<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ShippingSettlement */

$this->title = '物流结算: '.$model->settlement_number;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventorys-view box box-primary">
    <div class="box-header">
        <button type="submit" class="btn btn-primary back" style="margin-bottom: 10px;margin-right: 10px;margin-top: 10px;">返回</button>
        <?php if($model->status == 1):?>
            <button class="btn btn-success" onclick="confirm_inv(<?=$model->id?>)">确认</button>
            <button class="btn btn-success" onclick="delete_inv(<?=$model->id?>)">作废</button>
        <?php endif;?>

    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'settlement_number',
                'lc',
                'back_total',
                'other_fee',
                [
                    'attribute' => 'uid',
                    'value' => function ($model) {
                        $user = \app\models\User::findOne($model->uid);
                        return $user->name;
                    }
                ],
                [
                    'attribute' => 'status',
                    'value' => function($model){
                        return \app\models\ShippingSettlement::$status_arr[$model->status];
                    }
                ],
                'currency',
                'date_time',
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
        </tr>
        <?php foreach($items as $item):?>
            <tr>
                <td><?=++$i?></td>
                <td><?=$item->id_order?></td>
                <td><?=$item->lc_number?></td>
                <td><?=$item->back_order_total?></td>
                <td><?=$item->cod_fee?></td>
                <td><?=$item->shipping_fee?></td>
                <td><?=$item->other_fee?></td>
                <td><?=$item->currency?></td>
            </tr>
        <?php endforeach;?>
    </table>
    <div></div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . "/js/layer/layer.js", ['depends' => ['app\assets\AppAsset']]); ?>
<script>
    function confirm_inv(id) {
        layer.confirm('您确定要更新为确认状态吗?',{btn: ['确定', '取消'],title:"提示"}, function(){
            $.ajax({
                type: "post",
                url:"/shipping-settlement/confirm",
                data:{
                    'id':id
                },
                async:false,
                success:function(data) {
                    layer.msg(data.msg);
                    window.location.reload();
                }
            });
        });
    }

    function delete_inv(id) {
        layer.confirm('您确定要更新为作废吗?',{btn: ['确定', '取消'],title:"提示"}, function(){
            $.ajax({
                type: "post",
                url:"/shipping-settlement/delete-settlement",
                data:{
                    'id':id
                },
                async:false,
                success:function(data) {
                    layer.msg('执行成功');
                    window.location.reload();
                }
            });
        });
    }


    $(".back").on('click',function () {
        window.location.assign('/shipping-settlement/index');
    });
</script>
