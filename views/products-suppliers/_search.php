<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductsSuppliersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-suppliers-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'supplier_id') ?>

    <?= $form->field($model, 'sku') ?>

    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'min_buy') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'deliver_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
