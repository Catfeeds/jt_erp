<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductComment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-comment-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'website_id')->label('站点ID(多个ID用空格分开)')->textInput(['value' => $websiteId]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model,'body')->widget('kucha\ueditor\UEditor',[]);?>

        <?= $form->field($model, 'isshow')->textInput(['value' => 1]) ?>

        <?= $form->field($model, 'add_time')->textInput(['value' => date("Y-m-d H:i:s")]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
