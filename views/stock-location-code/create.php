<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StockLocationCode */

$this->title = 'Create Stock Location Code';
$this->params['breadcrumbs'][] = ['label' => 'Stock Location Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-location-code-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
