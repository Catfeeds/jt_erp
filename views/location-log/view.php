<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LocationLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Location Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-log-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
                'order_id',
                'sku',
                'qty',
                'stock_code',
                'location_code',
                'uid',
                'create_date',
            ],
        ]) ?>
    </div>
</div>
