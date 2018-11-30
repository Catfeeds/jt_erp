<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LocationStock */

$this->title = 'Update Location Stock: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Location Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="location-stock-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
