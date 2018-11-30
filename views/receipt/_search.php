<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PurchasesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchases-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_number') ?>

    <?= $form->field($model, 'create_time') ?>

    <?= $form->field($model, 'amaount') ?>

    <?= $form->field($model, 'supplier') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'uid') ?>

    <?php // echo $form->field($model, 'platform') ?>

    <?php // echo $form->field($model, 'platform_order') ?>

    <?php // echo $form->field($model, 'platform_track') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
