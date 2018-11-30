<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LocationStock */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="location-stock-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'stock_code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'area_code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'location_code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'stock')->textInput() ?>

        <?= $form->field($model, 'create_date')->textInput() ?>

        <?= $form->field($model, 'update_date')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
