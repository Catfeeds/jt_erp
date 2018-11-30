<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StockLocationArea */

$this->title = 'Create Stock Location Area';
$this->params['breadcrumbs'][] = ['label' => 'Stock Location Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-location-area-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
