<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StockLogs */

$this->title = 'Update Stock Logs: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-logs-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
