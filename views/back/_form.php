<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\dialog\Dialog;
/* @var $this yii\web\View */
/* @var $model app\models\Back */
/* @var $form yii\widgets\ActiveForm */
echo Dialog::widget();
?>

<div class="back-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'back')->textInput(['maxlength' => true, 'value' => $back]) ?>

    <?= $form->field($model, 'order_number')->textInput(['maxlength' => true, 'placeholder' => '若有关联的采购单，请填写采购单号']) ?>

    <?= $form->field($model, 'type')->dropDownList(['1' => '仅退款', '2' => '退货退款']) ?>

    <div class="form-group field-purchases-supplier has-success">
        <label class="control-label" for="status">退货状态</label>
        <?php
            if ($model->type == 1 ) {
                echo $model->status == 1? '待退款': $model->status_arr[$model->status];
            } else {
                echo $model->status_arr[$model->status];
            }
        ?>
    </div>

    <?= $form->field($model, 'consignee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'express')->textInput(['maxlength' => true, 'placeholder' => '发货有物流单号需维护物流单号，若仅退款，请填写"仅退款，没物流单号"']) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expressPrice')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount_real')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true, 'placeholder' => '已退款的请维护退款流水号']) ?>

    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>


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
            
            <?php if($backItems):foreach($backItems as $k => $item):?>
            
                <?php if($model->status == 0) : ?>
                    <tr class="<?= $item['sku']?>">
                        <td><img src="<?= $item['image'] ?>" width="68" height="52"/></td>
                        <td><?= $item['sku'] ?></td>
                        <td><?= $item['color'] ?></td>
                        <td><?= $item['size'] ?></td>
                        <input type="hidden" name="items_id<?= $k ?>" value="<?= $item['id'] ?>"/>
                        <td><input name="qty<?= $k ?>" value="<?= $item['qty'] ?>"/></td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $item['buy_link'] ?></td>
                        <td><?= $item['notes'] ?></td>
                    </tr>
                <?php else :?>
                    <tr class="<?= $item['sku']?>">
                        <td><img src="<?= $item['image'] ?>" width="68" height="52"/></td>
                        <td><?= $item['sku'] ?></td>
                        <td><?= $item['color'] ?></td>
                        <td><?= $item['size'] ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $item['buy_link'] ?></td>
                        <td><?= $item['notes'] ?></td>
                    </tr>
                <?php endif;?>
            <?php endforeach;endif ;?>
            <input type="hidden" name="count" value="<?= count($backItems) ?>"/>
        </table>

        <div <?php if($backItems){ echo 'style="display:none"'; }?>>
            SKU：<input type="text" id="add_sku">
            数量：<input type="text" id="add_qty">
            说明：<input type="text" id="add_notes">
            <button onclick="addSku()" type="button" class="btn btn-success">增加SKU</button>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton('保存修改', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    // 添加sku jieson 2018.10.24
    function addSku()
    {
        if ($("#add_sku").val() === '') {
            krajeeDialog.alert("sku不能为空！");
        } else {
            $.post("/back/add-sku", {sku:$("#add_sku").val(), qty:$("#add_qty").val(), notes: $("#add_notes").val()}, function (data) {
                var res = eval("("+data+")");
                if(res.status == 1)
                {
                    var str = "<tr class='"+res.sku+"'>";
                    str+= "<td><img src='"+res.image+"' width='68' hei='52'></td>";
                    str+= "<td>"+res.sku+"</td>";
                    str+= "<td>"+res.color+"</td>";
                    str+= "<td>"+res.size+"</td>";
                    str+= "<td>"+res.qty+"</td>";
                    str+= "<td>"+res.price+"</td>";
                    str+= "<td>"+res.buy_link+"</td>";
                    str+= "<td>"+res.notes+"</td>";
                    str+= "<td><button type='button' onclick='delSku(\""+res.sku+"\")'>删除</button></td>";
                    str+= "<input type='hidden' name='skuData[]' value='"+JSON.stringify(res.tempData)+"' />";
                    str+= "</tr>";
                    $(".skuData").append(str);
                    // sessionStorage.setItem("skuData", res);
                } else if (res.status == -1){
                    krajeeDialog.alert(res.msg);
                }
            });
        }
    }
    
    // 删除sku jieson 2018.10.24
    function delSku(sku)
    {
        $("."+sku).hide().remove();
    }
</script>

