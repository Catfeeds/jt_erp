<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SkuBoxs */

$this->title = 'Update Sku Boxs: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sku Boxs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sku-boxs-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
