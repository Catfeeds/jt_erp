<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */
/* @var $data_arr app\models\Orders */
/* @var $spu_arr app\models\Orders */
/* @var $order_info app\models\Orders */

$this->title = 'Update Orders: ' . $model[0]['order_id'];
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model[0]['order_id'], 'url' => ['view', 'id' => $model[0]['order_id']]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orders-update">
    <?= $this->render('_form', [
        'model' => $model,
        'data_arr' => $data_arr,
        'spu_arr' => $spu_arr,
        'order_info' => $order_info,
    ]) ?>

</div>
