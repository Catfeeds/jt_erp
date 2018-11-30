<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdFee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-fee-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'website_id')->textInput() ?>

        <?= $form->field($model, 'ad_total')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'ad_date')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
