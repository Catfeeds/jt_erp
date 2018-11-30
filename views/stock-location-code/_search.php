<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StockLocationCodeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-location-code-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'stock_code') ?>

    <?= $form->field($model, 'area_code') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'info') ?>

    <?php // echo $form->field($model, 'uid') ?>

    <?php // echo $form->field($model, 'create_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>