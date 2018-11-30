<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\dialog\Dialog;
/* @var $this yii\web\View */
/* @var $model app\models\Purchases */

$this->title = '快递单: ' . $track;
$this->params['breadcrumbs'][] = ['label' => '收货单', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $track, 'url' => ['track', 'id' => $track]];
echo Dialog::widget();////krajeeDialog.alert("测试");

?>
<div class="purchases-update">
    <div class="box-header with-border">
        <button type="button" onclick="printSkuLab()" class="btn btn-primary">打印标签</button>
        <!-- <button type="button" onclick="afterCollect()" class="btn btn-primary">收完</button> -->
        <button type="button" onclick="preCollected()" title="先收货上架再点击预收货完成" class="btn btn-success btn-flat">预收货完成</button>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <?php
    foreach($orders as $order):
        $items_list = $PurchasesItems->find()->where(['purchase_number'=>$order->order_number])->asArray()->all();
    ?>
    <?= $this->render('_track', [
        'model'      => $order,// 单个采购单
        'items_list' => $items_list,// 一个采购单的详情，里面有一个以上的sku
        'track'      => $track // 物流单
    ]) ?>
    <?php
    endforeach;
    ?>
    <?= $this->render('float_feedback',['data' => $feedback]) ;?>
    <div class="box-footer">
        <button class="btn btn-success" onclick="">提交收货数据</button>
        <input type="hidden" name="track_number" value="<?=trim($track);?>">
    </div>
    <?php ActiveForm::end(); ?>
</div>
<style>
    tbody tr:hover td { background-color:yellow;cursor: pointer}
</style>
<script>
    function printSkuLab() {
        var sku=''
        $.each($("input[name='sku_lab[]']"), function(i,n){
            sku += ($(n).val()) + "|";
        })
        window.open('/receipt/print-sku-code?sku=' + sku);
    }
    function checkQty(obj) {
        if($(obj).val()!=$(obj).attr('data-get'))
        {
            $(obj).attr('style', 'background-color:red');
            alert("请确认收货数量是否正确");
        }else{
            $(obj).attr('style', 'background-color:none');
        }

    }
    //收货
    function updateDeliver(obj) {
        $.get('/receipt/update-qty', {
            action: 'deliver',
            id: $(obj).attr('data-id'),
            qty: $(obj).val()
        }, function (data) {
            alert(data)
        })
    }

    //退货
    function updateRefound(obj) {
        $.get('/receipt/update-qty', {
            action: 'refound',
            id: $(obj).attr('data-id'),
            qty: $(obj).val()
        }, function (data) {
            alert(data)
        })
    }

    //jieson 2018.10.10 收完
    function afterCollect($order_number)
    {
        krajeeDialog.confirm("确定收完了吗?", function (result) {
            if (result) {
                krajeeDialog.alert('success!');
                <?php sleep(2) ?>
                location.reload();
            }
        });
    }

    // jieson 2018.10.11 预收货完成
    function preCollected()
    {
        krajeeDialog.confirm("确定预收货完成吗?", function (result) {
            if (result) {
                var track_number = $('input[name=track_number]').val();
                window.open("/receipt/pre-collect?track_number=" + track_number);
                //krajeeDialog.alert('success!');
                // 进行收货清点若有异常则跳转到反馈页面；没有异常则表示收货完成，状态变为已入库、
                // $.post("/receipt/pre-collect", {track_num:track_num}, function(res){
                //     krajeeDialog.alert(res);
                // });
            }
        });
    }
</script>