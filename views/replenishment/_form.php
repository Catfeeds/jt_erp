<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Replenishment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="replenishment-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'orders_id')->textInput() ?>

        <?= $form->field($model, 'sku_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'supplement_number')->textInput() ?>

        <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'create_time')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
