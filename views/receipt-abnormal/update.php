<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReceiptAbnormal */

$this->title = '更新异常收货单: ' . $model->track_number;
$this->params['breadcrumbs'][] = ['label' => '异常收货单', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="receipt-abnormal-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
