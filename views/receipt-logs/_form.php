<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReceiptLogs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="receipt-logs-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'track_number')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'create_date')->textInput() ?>

        <?= $form->field($model, 'create_uid')->textInput() ?>

        <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'status')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
