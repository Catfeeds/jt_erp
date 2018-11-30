<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SkuBoxs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sku-boxs-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'p_sku')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 's_sku')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status')->dropDownList($model->status_array) ?>


    </div>
    <div class="box-footer">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
