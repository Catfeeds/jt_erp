<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Requisitions */

$this->title = '修改调拨单: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Requisitions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="requisitions-update">

    <?= $this->render('_form', [
        'model' => $model,
        'items_list' => $items_list,
        'stock_list' => $stock_list
    ]) ?>

</div>
