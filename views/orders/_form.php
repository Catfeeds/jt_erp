<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'website_id')->textInput() ?>

        <?= $form->field($model, 'product')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'district')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'post_code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'create_date')->textInput() ?>

        <?= $form->field($model, 'pay')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'status')->dropDownList([
                            1 => '待确认',
                            2 => '已经确认',
                            3 => '已采购',
                            4 => '已发货',
                            5 => '签收',
                            6 => '拒签',
                            7 => '已入库',
                            8 => '已打包',
                            9 => '已回款',
                            10 => '取消订单',
                        ]) ?>

        <?= $form->field($model, 'qty')->textInput() ?>

        <?= $form->field($model, 'total')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'lc')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'lc_number')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'shipping_date')->textInput() ?>

        <?= $form->field($model, 'delivery_date')->textInput() ?>

        <?= $form->field($model, 'cost')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'channel_type')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'purchase_time')->textInput() ?>

        <?= $form->field($model, 'back_total')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'cod_fee')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'shipping_fee')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'ads_fee')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'other_fee')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'comment_u')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'back_date')->textInput() ?>

        <?= $form->field($model, 'update_time')->textInput() ?>

        <?= $form->field($model, 'is_lock')->textInput() ?>

        <?= $form->field($model, 'copy_admin')->textInput() ?>

        <?= $form->field($model, 'uid')->textInput() ?>

        <?= $form->field($model, 'money_status')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
