<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProductsBase */

$this->title = 'Create Products Base';
$this->params['breadcrumbs'][] = ['label' => 'Products Bases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-base-create">

    <?= $this->render('_form', [
    'model' => $model,
    'categories' => $categories,
    ]) ?>

</div>
