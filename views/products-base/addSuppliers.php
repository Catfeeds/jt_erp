<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProductsBase */

$this->title = '产品增加供应商';
$this->params['breadcrumbs'][] = ['label' => '添加供应商', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-base-create">

    <?= $this->render('_formAddSuppliers', [
    'model' => $model,
    'suppliers' => $suppliers,
    'product_supplier'=>$product_supplier


    ]) ?>

</div>
