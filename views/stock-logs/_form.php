<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StockLogs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-logs-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'order_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'qty')->textInput() ?>

        <?= $form->field($model, 'cost')->textInput() ?>

        <?= $form->field($model, 'uid')->textInput() ?>

        <?= $form->field($model, 'log_type')->textInput() ?>

        <?= $form->field($model, 'create_date')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
