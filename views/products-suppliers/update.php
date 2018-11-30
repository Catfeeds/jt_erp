<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProductsSuppliers */

$this->title = 'Update Products Suppliers: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Products Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="products-suppliers-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
