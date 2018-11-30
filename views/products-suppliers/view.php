<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProductsSuppliers */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Products Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-suppliers-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id,'spu_id'], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id,'spu_id'], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'supplier_id',
                'sku',
                'url:ntext',
                'min_buy',
                'price',
                'deliver_time:datetime',
            ],
        ]) ?>

    </div>
</div>
