<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReceiptAbnormal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="receipt-abnormal-form">

    <?php $form = ActiveForm::begin(); ?> 

    <?= $form->field($model, 'track_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model,'contents')->widget('kucha\ueditor\UEditor',[]);?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
