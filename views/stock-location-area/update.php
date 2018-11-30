<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StockLocationArea */

$this->title = 'Update Stock Location Area: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Location Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-location-area-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
