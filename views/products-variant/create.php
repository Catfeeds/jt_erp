<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProductsVariant */

$this->title = 'Create Products Variant';
$this->params['breadcrumbs'][] = ['label' => 'Products Variants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-variant-create">

    <?= $this->render('_form', [
    'model' => $model,
    'suppliers'=>$suppliers,
    'product_supplier'=>$product_supplier
    ]) ?>

</div>
