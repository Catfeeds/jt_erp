<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'level')->dropdownList([0 => "第一级", 1 => "第二级", 2 => "第三级"]) ?>

        <?= $form->field($model, 'pid')->dropdownList($categories) ?>

        <?= $form->field($model, 'cn_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'en_name')->textInput(['maxlength' => true]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('提交分类', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
