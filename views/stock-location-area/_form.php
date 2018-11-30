<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StockLocationArea */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-location-area-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'area_code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'area_name')->textInput(['maxlength' => true]) ?>


    </div>
    <div class="box-footer">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
