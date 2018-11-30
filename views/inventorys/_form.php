<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Inventorys */
/* @var $form yii\widgets\ActiveForm */

$wmodel = new \app\models\Warehouse();
?>

<div class="inventorys-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'stock')->dropDownList($wmodel->stockCods()) ?>

        <?= $form->field($model, 'inventory_date')->textInput() ?>

        <?= $form->field($model, 'is_all')->dropdownList([1 => "部分盘", 2 => "全盘"]) ?>

        <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
