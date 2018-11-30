<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrdersItem */

$this->title = 'Update Orders Item: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orders-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
