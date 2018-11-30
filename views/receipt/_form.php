<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Purchases */
/* @var $form yii\widgets\ActiveForm */
$skuModel = new \app\models\ProductsVariant();
?>

<div class="purchases-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <div class="form-group field-purchases-supplier has-success">
            <label class="control-label" for="purchases-supplier">供应商</label>
            <?=$model->supplier;?>   </div>
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
                switch ($model->status) {
                    case 0:
                        echo '草稿';
                        break;
                    case 1:
                        echo '已确认';
                        break;
                    case 2:
                        echo '已采购';
                        break;
                    case 3:
                        echo '已收货';
                };
            ?>
        </div>


        <?= $form->field($model, 'platform')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'platform_order')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'track_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'platform_track')->textInput(['maxlength' => true]) ?>
        <?php if($model->status == 0) : ?>
            <?= $form->field($model, 'platform_order')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'platform_track')->textInput(['maxlength' => true]) ?>
        <?php endif;?>

    </div>
    <div class="box-body table-responsive" >
        <table class="table table-bordered" >
            <td></td>
            <th>sku</th>
            <th></th>
            <th></th>
            <th>采购量</th>
            <th>实收数量</th>
            <th>退货数量</th>
            <?php   if($items_list) :$i=0;foreach($items_list as $key=>$list):
                $sku_info = $skuModel->find()->where(['sku' => $list['sku']])->one();
                ?>
                <tr>
                    <td><img width="100" src="<?=$sku_info->image?>"> </td>
                    <td>
                        <?php echo $list['sku'];?>
                        <input type="hidden" class="input-small" name="receipt[<?php echo $i;?>][sku]" value="<?php echo $list['sku'];?>" placeholder="">
                    </td>
                    <td><?=$sku_info->color?> </td>
                    <td><?=$sku_info->size?> </td>
                    <td>
                        <?php echo $list['qty'];?>
                    </td>

                    <td>
                        <input type="text" class="input-small" onchange="updateDeliver(this)" data-id="<?=$list['id']?>" name="receipt[<?php echo $i;?>][delivery_qty]" value="<?php echo $list['delivery_qty'];?>" placeholder="实收数量">
                    </td>
                    <td>
                        <input type="text" class="input-small" onchange="updateRefound(this)" data-id="<?=$list['id']?>" name="receipt[<?php echo $i;?>][refound_qty]" value="<?php echo $list['refound_qty'];?>" placeholder="退货数量">
                    </td>

                </tr>
                <?php $i++;endforeach;endif;?>
        </table>

    </div>
    <div class="box-footer">
        <?php if($model->status != 3) : ?>

        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
        <?php endif;?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    //收货
    function updateDeliver(obj) {
        $.get('/receipt/update-qty', {action:'deliver', id:$(obj).attr('data-id'), qty:$(obj).val()}, function (data) {
            alert(data)
        })
    }
    //退货
    function updateRefound(obj) {
        $.get('/receipt/update-qty', {action:'refound', id:$(obj).attr('data-id'), qty:$(obj).val()}, function (data) {
            alert(data)
        })
    }
</script>
