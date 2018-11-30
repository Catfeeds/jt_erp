<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProductsSuppliers */

$this->title = 'Create Products Suppliers';
$this->params['breadcrumbs'][] = ['label' => 'Products Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-suppliers-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
