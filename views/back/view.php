<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Back */

$this->title = '退库单：'.$model->back;
$this->params['breadcrumbs'][] = ['label' => '退货单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="back-view">
    <p>
        <?= $model->status !=3? Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']):''?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除此项吗?',
                'method' => 'post',
            ],
        ]) ?>
        <?= $model->status == 1 && $model->type == 2? Html::a('库房确认', 'javascript:void(0);', ['class' => 'btn btn-success', 'onclick' => 'sureBack()']) : ''?>
        <?= $model->status == 0? Html::a('采购确认', 'javascript:void(0);', ['class' => 'btn btn-success', 'onclick' => 'purchaseSureBack()']) : ''?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'order_number',
            'consignee',
            'phone',
            'address',
            'express',
            'amount',
            'expressPrice',
            'amount_real',
            'serial_number',
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->type ==1?'仅退款':'退货退款';
                }
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    if ($model->type == 1 ) {
                        return $model->status == 1? '待退款': $model->status_arr[$model->status];
                    } else {
                        return $model->status_arr[$model->status];
                    }
                    
                }
            ],
            [
                'attribute' => 'create_uid',
                'value' => function ($model) {
                    $user = \app\models\User::findOne($model->create_uid);
                    return $user->name;
                }
            ],
            'notes',
            'create_time',
        ],
    ]) ?>
    
</div>
<h4><b>操作记录：</b></h4>
<div class="box-body table-responsive" >
    <table class="table table-bordered skuData" >

        <th>操作人</th>
        <th>记录</th>
        <th>状态</th>
        <th>操作时间</th>

        <?php foreach($backLogs as $item) { ?>
            <tr>
                <td>
                <?php $user = \app\models\User::findOne($item['create_uid']);
                    echo $user->name; ?>
                </td>
                <td><?= $item['records'] ?></td>
                <td><?= $model->status_arr[$item['status']] ?></td>
                <td><?= $item['create_time'] ?></td>
            </tr>
        <?php } ;?>
    </table>
</div>
<h4><b>SKU信息：</b></h4>
<div class="box-body table-responsive" >
    <table class="table table-bordered skuData" >

        <th>图片</th>
        <th>sku</th>
        <th>颜色</th>
        <th>尺寸</th>
        <th>退库数量</th>
        <th>单价</th>
        <th>购买链接</th>
        <th>说明</th>

        <?php foreach($backItems as $item) { ?>
            <tr>
                <td><img src="<?= $item['image'] ?>" width="68" height="52"/></td>
                <td><?= $item['sku'] ?></td>
                <td><?= $item['color'] ?></td>
                <td><?= $item['size'] ?></td>
                <td><?= $item['qty'] ?></td>
                <td><?= $item['price'] ?></td>
                <td><?= $item['buy_link'] ?></td>
                <td><?= $item['notes'] ?></td>
            </tr>
        <?php } ;?>
    </table>
</div>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . "/js/layer/layer.js", ['depends' => ['app\assets\AppAsset']]); ?>
<script>
    // 库房确认 jieson 2018.10.30
    function sureBack()
    {
        var id = "<?= $model->id ?>";
        layer.confirm('已经确认库位及数量吗？', function(yes){
            $.get("/back/sure-back", {id:id}, function(res){
                if (res == 1) {
                    layer.msg('确认成功！发货完成后请维护物流单号到该退货单',{icon:1}, function(){window.location.reload()});
                } else {
                    layer.msg('确认失败，请稍候重试！');
                }
            });
        });
    }

    // 采购确认 jieson 2018.10.30
    function purchaseSureBack()
    {
        var id = "<?= $model->id ?>";
        layer.confirm('已经确认了吗？点击确认之后不能再修改退货单了！', function(yes){
            $.get("/back/purchase-sure-back", {id:id}, function(res){
                if (res == 1) {
                    layer.msg('确认成功！',{icon:1}, function(){window.location.reload()});
                } else if( res == -1){
                    layer.msg('系统可用库存不足，请核实再重试！', {icon:0});
                } else {
                    layer.msg('确认失败，请稍候重试！');
                }
            });
        });
    }
</script>
