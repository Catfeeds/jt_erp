<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\dialog\Dialog;

/* @var $this yii\web\View */
/* @var $model app\models\Purchases */
/* @var $form yii\widgets\ActiveForm */

$skuModel = new \app\models\ProductsVariant();
// 弹框初始设置
echo Dialog::widget();
?>

<div class="purchases-form box box-primary">
    <div class="form-actions">
        <button type="submit" class="btn btn-primary back" style="margin-bottom: 10px;margin-right: 10px;margin-top: 10px;width: 70px;">返回</button>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <?=$form->field($model, 'supplier')->textInput();?>
        <?php if($model->status == 0) {
            echo $form->field($model, 'amaount')->textInput(['maxlength' => true]) ;
        }else {
        echo '<div class="form-group field-purchases-supplier has-success">
            <label class="control-label" for="purchases-supplier">总价</label>
            '.$model->amaount.'   </div>';

        } ?>
        <div class="form-group field-purchases-supplier has-success">
            <label class="control-label" for="status">采购状态</label>
            <?php
                echo $model->status_array[$model->status];
            ?>
        </div>

        <div class="form-group">
            <label class="control-label">预计到货时间</label>
            <?php
            echo DateTimePicker::widget([
                'name' => 'Purchases[delivery_time]',
                'options' => ['placeholder' => '预计到货时间-开始', 'style' => 'width:200px;'],
                //注意，该方法更新的时候你需要指定value值
                'value' => $model->delivery_time,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'startView'=>2,    //其实范围（0：日  1：天 2：年）
                    'minView'=>2,  //最大选择范围（年）
                    'maxView'=>2,  //最小选择范围（年）
                ]
            ]);
            ?>

        </div>
        <?= $form->field($model, 'shipping_amount')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'platform')->textInput(['maxlength' => true]) ?>

        <?php if($model->status > 0) : ?>
            <?= $form->field($model, 'platform_order')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'track_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'platform_track')->textInput(['maxlength' => true]) ?>
        <?php endif;?>

        <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>

    </div>

    <div class="box-body table-responsive" >
        <button class="btn btn-primary btn-flat" type="button" onclick="createNewOrder()">拆单</button>&nbsp;&nbsp;&nbsp;&nbsp;
        <button class="btn btn-primary btn-flat" type="button" onclick="createNewOrderBySpu()">SPU拆单</button>
        <table class="table table-bordered" >
            <th></th>
            <th>图片</th>
            <th>sku</th>
            <th>颜色</th>
            <th>尺寸</th>
            <th>采购数量</th>
            <th>单价</th>
            <th>购买链接</th>
            <th>说明</th>
            <th>3天销量</th>
            <th>7天销量</th>
            <th></th>
            <?php
            if($items_list) :$i=0;foreach($items_list as $key=>$list):
                $sku_info = $skuModel->find()->where(['sku' => $list['sku']])->one();
            ?>
                <?php if($model->status == 0) : ?>
                <tr>
                    <td>
                        <input class="checkbox" type="checkbox" name="sku_ids" value="<?=$list['id']?>">
                        <input type="hidden" class="input-small" name="receipt[<?php echo $i;?>][id]" value="<?php echo $list['id'];?>" placeholder="">
                    </td>
                    <td><img width="100" src="<?=$sku_info->image?>"> </td>
                    <td>
                        <?php echo $list['sku'];?>
                        <input type="hidden" class="input-small" name="receipt[<?php echo $i;?>][sku]" value="<?php echo $list['sku'];?>" placeholder="">
                    </td>
                    <td><?=$sku_info->color?> </td>
                    <td><?=$sku_info->size?> </td>

                    <td>
                        <input type="text" class="input-small" name="receipt[<?php echo $i;?>][qty]" value="<?php echo $list['qty'];?>" placeholder="数量">
                    </td>
                    <td>
                        <input type="text" class="input-small" name="receipt[<?php echo $i;?>][price]" value="<?php echo $list['price'];?>" placeholder="单价">
                    </td>
                    <td>
                        <input type="text" class="input-small" name="receipt[<?php echo $i;?>][buy_link]" value="<?php echo $list['buy_link'];?>" placeholder="购买链接">
                    </td>
                    <td>
                        <input type="text" class="input-small" name="receipt[<?php echo $i;?>][info]" value="<?php echo $list['info'];?>" placeholder="说明">
                    </td>
                    <td>
                        <?php echo $list['qty3_num'];?>
                    </td>
                    <td>
                        <?php echo $list['qty7_num'];?>
                    </td>
                    <td><button type="button" onclick="delSku(<?=$list['id']?>)">删除</button></td>

                </tr>
                    <?php else :?>
                    <tr>
                        <td></td>
                        <td><img width="100" src="<?=$sku_info->image?>" </td>
                        <td>
                            <?php echo $list['sku'];?>
                        </td>
                        <td><?=$sku_info->color?> </td>
                        <td><?=$sku_info->size?> </td>
                        <td>
                            <?php echo $list['qty'];?>
                        </td>
                        <td>
                            <?php echo $list['price'];?>
                        </td>
                        <td>
                            <?php echo $list['buy_link'];?>
                        </td>
                        <td>
                            <?php echo $list['info'];?>
                        </td>
                        
                        <td>
                        <?php echo $list['qty3_num'];?>
                        </td>
                        <td>
                        <?php echo $list['qty7_num'];?>
                        </td>
                        <td></td>

                    </tr>
                    <?php endif;?>
                <?php $i++;endforeach;endif;?>
        </table>

        <?php if($model->status == 0) : ?>
        <div>
            SKU：<input type="text" id="add_sku">
            数量：<input type="text" id="add_qty">
            <button onclick="addSku()" type="button" class="btn btn-success">增加SKU</button>
        </div>
            <input type="hidden" name="order_number" value="<?= $model->order_number;?>">
        <?php endif;?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('保存修改', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    //页面跳转到审核页
    $(".back").on('click',function () {
        window.location.assign('/purchases/index');
    });

    function delSku(id) {
        if(confirm("你确认要删除这条记录"))
        {
            $.get("/purchases/del-sku", {id:id}, function (data) {
                if(data==1)
                {
                    location.reload();
                }
            });
        }

    }
    //添加SKU
    function addSku() {
        if($("#add_sku").val() && $("#add_qty").val())
        {
            $.get("/purchases/add-sku", {sku:$("#add_sku").val(), qty:$("#add_qty").val(), id:<?=$model->id?>}, function (data) {
                if(data==1)
                {
                    location.reload();
                }else{
                    krajeeDialog.alert("添加失败");
                    //alert('添加失败');
                }
            });
        }else{
            alert("SKU与数量不能为空");
        }

        
    }
    //拆单
    function createNewOrder(){
        var ids = '';
        var order_number = $("input[name='order_number']").val();
        $("input[name='sku_ids']:checked").each(function () {
            ids += $(this).val() + ',';
        });
        if(ids == ''){
            alert("请选择SKU");
            return false;
        }
        $.get("/purchases/create-new-order", {ids:ids,order_number:order_number}, function(data){
            alert(data);
            location.reload();
        })
    }

    //jieson 2018.10.05 设为已入库
    function setInware()
    {
        var id = <?=$model->id?>;
        krajeeDialog.confirm('确定入库吗?',function(result){
            if (result) {
                $.post("/purchases/set-inware", {id:id}, function(res){
                    krajeeDialog.alert("入库成功");
                });
                
            }
        });
    }
    //根据SPU进行拆单
    function createNewOrderBySpu(){
        var order_number = $("input[name='order_number']").val();
        $.post("/purchases/create-new-order-by-spu", {order_number:order_number}, function(data){
            console.log(data);
            alert(data);
            window.location.assign('/purchases/index');
        })

    }
</script>
