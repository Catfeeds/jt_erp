<?php 
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\dialog\DialogAsset;
use kartik\export\ExportMenu;
use Yii;
$skuModel = new \app\models\ProductsVariant();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>退库</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <link href="/assets/d9be4ce0/css/AdminLTE.min.css" rel="stylesheet">
    <style>
    table {background-color: transparent;border-spacing: 0;border-collapse: collapse;}
    .table-responsive {min-height: .01%;overflow-x: auto;}
    .box-body {border-top-left-radius: 0;border-top-right-radius: 0;border-bottom-right-radius: 3px;border-bottom-left-radius: 3px;padding: 10px;}
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {border: 1px solid #f4f4f4;}
    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {border-top: 1px solid #f4f4f4;}
    .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {border: 1px solid #ddd;}
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;}
    .table {width: 100%;max-width: 100%;margin-bottom: 20px;}
    .table-responsive span{width:90px;position:relative;}
    .btn-success {color: #fff;background-color: #5cb85c;border-color: #4cae4c;}
    .btn {display: inline-block;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: normal;line-height: 1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;border: 1px solid transparent;border-radius: 4px;}
    .form-control {border-radius: 0;box-shadow: none;border-color: #d2d6de;}
    .form-control {width: 180px;height: 20px;padding: 2px 4px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);-webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;-o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;}
    </style>
</head>
<body>
<div style="background-color: #ecf0f5;">
<section class="content">
<form class="backForm">
<div class="purchases-view box box-primary">
    <div class="box-body table-responsive">
        <table class="table table-bordered">
            <th></th>
            <th>sku</th>
            <th>颜色</th>
            <th>尺寸</th>
            <th>采购数量</th>
            <th>单价</th>
            <th>购买链接</th>
            <th>实收数量</th>
            <th>退货数量</th>
            <?php
            if($items_list) :$i=0;foreach($items_list as $key=>$list):
                $sku_info = $skuModel->find()->where(['sku' => $list['sku']])->one();
                ?>
                <tr>
                    <td><img width="100" src="<?=$sku_info->image?>" </td>
                    <td>
                        <?php echo $list['sku'];?>
                        <input type="hidden" name="sku<?= $key ?>" value="<?php echo $list['sku'];?>" />
                    </td>
                    <td><?=$sku_info->color?> </td>
                    <td><?=$sku_info->size?> </td>
                    <td>
                        <?php echo $list['qty'];?>
                    </td>
                    <td >
                        <span class='price'><?php echo $list['price'];?></span>
                    </td>
                    <td>
                        <?php
                        if($list['buy_link'])
                        {
                            echo '<a href="'.$list['buy_link'].'">点击购买</a>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $list['delivery_qty'];?>
                    </td>
                    <td>
                        <input type="text" name="refound_qty<?= $key ?>" placeholder="必须整数，且小于 <?= $model->status == 3?$list['delivery_qty']:$list['qty']?>" class="form-control refound_qty"/>
                    </td>
                    
                </tr>
                <?php $i++;endforeach;endif;?>
                <input type="hidden" name="count" value="<?php echo count($items_list) ?>"/>
        </table>
    </div>
</div>
<div class="box-body table-responsive">
    <input type="hidden" name="order_id" value="<?= $model->id ?>"/>
    <p><span><em style="color:#dd4b39 ">*</em>采购成本：</span><input type="text" name="amount" class="form-control amount"/></p>
    <p><span><em style="color:#dd4b39 ">*</em>实际金额：</span><input type="text" name="amount_real" class="form-control amount_real"/></p>
    <p><span><em style="color:#dd4b39 ">*</em>退货类型：</span><select name="type"><option value='0'>选择退货类型</option><option value='1'>仅退款</option><option value='2'>退货退款</option></select></p>
    <p><span style="padding-right:38px">备注：</span><input type="text" name="notes" class="form-control"/></p>
</div>
<form>
</section>
<div class="box-footer">
    <?= Html::Button('提交', ['class' => 'btn btn-success btn-flat', 'onclick' => "submitBack()"]) ?>
    <!-- <button type="button" class="btn btn-success btn-flat" onclick="submitBack()">提交 </button> -->
</div>
</div>
<input type="hidden" name="purchase_status" value="<?= $model->status ?>"/>
</body>
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/js/layer/layer.js"></script>
<script>
    // 退货数量 jieson 2018.10.27
    $(".refound_qty").each(function(i){
        $(this).blur(function(){
            var reg = new RegExp("^[0-9]*$");

            var purchase_status = $("input[name=purchase_status]").val();
            var qty = Number($(this).parent().siblings().eq(4).text());// 采购数量
            var delivery_qty = Number($(this).parent().prev().text());; // 实收数量
            if (purchase_status == 3 && $(this).val() > delivery_qty) {
                $(this).focus();return false;
            } else if (purchase_status != 3 && $(this).val() > qty) {
                $(this).focus();return false;
            }

            if ($(this).val() === '0' || !reg.test($(this).val())) {
                $(this).focus();return false;
            }
            var amount = 0;
            $(".refound_qty").each(function(n){
                if ($(".refound_qty").eq(n).val() !== 0 || $(".refound_qty").eq(n).val() !== '') {
                    amount+= Number($(".refound_qty").eq(n).val() * $('.price').eq(n).text());
                }
            });
            $(".amount").val(amount.toFixed(2));
        })
    })

    // 提交jieson 2018.10.27
    function submitBack()
    {
        var is_pass = false;
        $(".refound_qty").each(function(i){
            if ($(this).val() != '0' && $(this).val() != '') {is_pass = true;}
        });
        if (!is_pass) {
            layer.msg('请填写退货数量！',{icon:0});return false;
        }

        if ($('.amount_real').val() === '' || $('.amount_real').val() === '0') {
            layer.msg('实际金额不能为空！',{icon:0},function(){$('.amount_real').focus()});return false;
        }

        if ($("select[name=type]").children("option:selected").val() == '0') {
            layer.msg('请选择退货类型',{icon:0});return false;
        }

        // 400错误，使用post提交的话，会认证Csrf，导致出现400
        $.get("/back/addback", $("form").serialize(), function(res){
            var res = eval("("+ res +")");
            if(res.status == 1 ){
                layer.msg('提交成功！即将跳转到退货页面...', {icon:1}, function(){
                    var flag = window.open("/back/view?id=" + res.backId, '_blank');// 如果不能弹窗那就是浏览器阻止了弹窗
                    if(flag == null) {
                        layer.msg("您的浏览器启用弹出窗口过滤功能！\n\n请暂时先关闭此功能！",function(){window.parent.location.reload();}) ;
                    } else {
                        window.parent.location.reload();
                    }
                })
            } else if (res.status == -1){
                layer.msg('可用库存不足！', {icon:0});
            } else {
                layer.msg('提交失败，稍候重试！', {icon:0});
            }
        });

    }
</script>
</html>