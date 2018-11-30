<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Purchases */

$this->title = '采购单: ' . $model->order_number;
$this->params['breadcrumbs'][] = ['label' => '采购单', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改采购单';
?>
<div class="purchases-update">
    <?= $this->render('_form', [
        'model' => $model,
        'items_list' =>$items_list
    ]) ?>

</div>
