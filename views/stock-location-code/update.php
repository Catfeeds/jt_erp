<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StockLocationCode */

$this->title = 'Update Stock Location Code: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Location Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-location-code-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
