<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StockLogs */

$this->title = 'Create Stock Logs';
$this->params['breadcrumbs'][] = ['label' => 'Stock Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-logs-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
