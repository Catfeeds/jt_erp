<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProductsVariant */

$this->title = 'Update Products Variant: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Products Variants', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="products-variant-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
