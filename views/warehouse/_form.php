<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Warehouse */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="warehouse-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'stock_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'stock_code')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'stock_type')->dropdownList([1 => "普通", 2 => "转运仓库"]) ?>

        <?= $form->field($model, 'status')->dropdownList([1 => "可用", 2 => "禁用"]) ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
