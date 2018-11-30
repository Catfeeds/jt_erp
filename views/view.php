<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LocationStock */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Location Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-stock-view box box-primary">
    <div class="box-header">
        <?= ''
//        Html::a('Delete', ['delete', 'id' => $model->id], [
//            'class' => 'btn btn-danger btn-flat',
//            'data' => [
//                'confirm' => 'Are you sure you want to delete this item?',
//                'method' => 'post',
//            ],
//        ])
        ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'stock_code',
                'area_code',
                'location_code',
                'sku',
                'stock',
                'create_date',
                'update_date',
            ],
        ]) ?>
    </div>
</div>
