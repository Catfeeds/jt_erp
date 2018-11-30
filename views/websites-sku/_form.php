<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WebsitesSku */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="websites-sku-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'website_id')->textInput() ?>

        <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'size')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sign')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'images')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'out_stock')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
