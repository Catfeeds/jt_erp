<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\dialog\Dialog;


/* @var $this yii\web\View */
/* @var $model app\models\Requisitions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requisitions-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <?php if(!$model->order_number) :?>
            <?php echo '<div class="form-group field-purchases-supplier has-success">
                <label class="control-label" for="purchases-supplier">调拨单号</label>
                '.date('YmdHis').'   </div>'; ?>
            <input type="hidden" name="Requisitions[order_number]" value="<?php echo date('YmdHis');?>">
        <?php else :?>
            <?= $form->field($model, 'order_number')->textInput(['maxlength' => true]) ?>
        <?php endif;?>
        <?= $form->field($model, 'order_type')->dropdownList(['库内调拨' => "库内调拨", '库间调拨' => "库间调拨", '退货调拨' => "退货调拨"])  ?>


        <div class="form-group field-orderssearch-lc">
            <label class="control-label" for="orderssearch-spu">调出仓</label>&nbsp;&nbsp;&nbsp;<span style="color: maroon"></span><br>
            <select name="Requisitions[out_stock]" style="height: 32px;width: 360px;">
                <?php foreach ($stock_list as $key => $row):?>

                        <option value="<?= $row['stock_name'];?>" <?php if($model->out_stock == $row['stock_name']) echo 'selected'; ?>><?= $row['stock_name'];?></option>
                <?php endforeach;?>
            </select>

            <div class="help-block"></div>
        </div>

        <div class="form-group field-orderssearch-lc">
            <label class="control-label" for="orderssearch-spu">调入仓</label>&nbsp;&nbsp;&nbsp;<span style="color: maroon"></span><br>
            <select name="Requisitions[in_stock]" style="height: 32px;width: 360px;">
                <?php foreach ($stock_list as $key => $row):?>

                    <option value="<?= $row['stock_name'];?>" <?php if($model->in_stock == $row['stock_name']) echo 'selected'; ?>><?= $row['stock_name'];?></option>

                <?php endforeach;?>
                <option value="退货仓" <?php if($model->in_stock == '退货仓') echo 'selected'; ?>>退货仓</option>
            </select>

            <div class="help-block"></div>
        </div>
        <?php if( Yii::$app->controller->action->id == 'update'):?>
            <div class="form-group field-purchases-supplier has-success">
                <label class="control-label" for="status">时间</label>
                <?php echo $model->create_date;?>
            </div>
            <div class="form-group field-purchases-supplier has-success">
                <label class="control-label" for="status">操作人</label>
                <?php
                    $user = \app\models\User::findOne($model->create_uid);
                    echo $user->name;
                ?>
            </div>
        <div class="form-group field-purchases-supplier has-success">
            <label class="control-label" for="status">状态</label>
            <?php
            switch ($model->order_status) {
                case 0:
                    echo '草稿';
                    break;
                case 1:
                    echo '已确认';
                    break;
                case 2:
                    echo '已完成';
                    break;
//                    case 3:
//                        echo '已收货';
            };
            ?>
        </div>
        <?php endif;?>

    </div>
    <?php if( Yii::$app->controller->action->id == 'update'):?>

    <div class="box-body table-responsive" >
        <table class="table table-bordered" >
            <th>SKU</th>
            <th>数量</th>
            <th></th>
            <?php
            if($items_list) :
                ?>
                <?php foreach($items_list as $key=>$list) : ?>
                <tr>

                    <td><?=$list['sku']?> </td>
                    <td><?=$list['qty']?> </td>
                    <td>
                    <?php if($model->order_status == 0) : ?>
                    <button type="button" onclick="delSku(<?=$list['id']?>)">删除</button>
                    <?php endif;?>
                    </td>
                </tr>

                <?php $i++;endforeach;endif;?>
            <?php if($model->order_status == 0) : ?>
                <tr >
                    <td>
                        <input type="text" id="z_sku" class="input-small" name="sku" placeholder="sku">
                    </td>
                    <td>
                        <input type="text" id="z_qty" class="input-small" name="qty" placeholder="数量">
                    </td>
                    <td><button type="button" onclick="addSku()">添加</button></td>

                </tr>
            <?php endif;?>
        </table>

    </div>
    <?php endif;?>
    <div class="box-footer">
        <?php if($model->order_status == 0) : ?>
            <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
            <?php if( Yii::$app->controller->action->id == 'update'):?>

                <?=Html::a('<span class="btn btn-info	">确认调拨</span>', '/requisitions/confirm-requisitions?id='.$model->id, ['title' => '确认调拨'] ) ;?>
            <?php endif;?>
        <?php elseif($model->order_status == 1):?>
            <?=Html::a('<span class="btn btn-info	">设置完成调拨</span>', '/requisitions/done-requisitions?id='.$model->id, ['title' => '设置完成调拨'] ) ;?>

        <?php endif;?>
    </div>
    <?php ActiveForm::end(); ?>
    <form  id="requisitions_post" action="" method="post">
        <input type="hidden" class="input-small" id="id" name="id" value="<?php echo $model->id;?>" placeholder="id">
        <input type="hidden" class="input-small" id="sku" name="sku" placeholder="sku">
        <input type="hidden" class="input-small" id="qty" name="qty" placeholder="数量">
        <input type="hidden" class="input-small" id="item_id" name="item_id" placeholder="明细ID">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

    </form>
</div>
<script>
    function addSku(){
      if($('#z_sku').val() == '') {
        alert('sku为空');return;
      }
      if($('#z_qty').val() == '') {
        alert('数量为空');return;
      }
      $('#sku').val($('#z_sku').val());
      $('#qty').val($('#z_qty').val());
      $('#requisitions_post').attr('action','/requisitions/add-sku');
      $('#requisitions_post').submit();


    }
    function delSku($id){
      var r=confirm("是否删除该调拨单明细");


        if(r == true){
          $('#item_id').val($id);
          $('#requisitions_post').attr('action','/requisitions/del-sku');
          $('#requisitions_post').submit();
          }


    }
  <?php $this->beginBlock('suppliers_js') ?>
        var id = '<?php echo $model->id;?>';
  <?php $this->endBlock() ?>
  <?php $this->registerJs($this->blocks['suppliers_js']); ?>
</script>