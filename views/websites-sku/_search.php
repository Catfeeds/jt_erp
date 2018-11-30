<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WebsitesSkuBaseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="websites-sku-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'website_id') ?>

    <?= $form->field($model, 'sku') ?>

    <?= $form->field($model, 'color') ?>

    <?= $form->field($model, 'size') ?>

    <?php // echo $form->field($model, 'sign') ?>

    <?php // echo $form->field($model, 'images') ?>

    <?php // echo $form->field($model, 'out_stock') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
