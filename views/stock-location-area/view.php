<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StockLocationArea */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Location Areas', 'url' => ['stock-location-area/index?stock_code='.$stock_code]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-location-area-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id,'stock_code'=>$stock_code], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id,'stock_code'=>$stock_code], [
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
                'stock_code',
                'area_code',
                'area_name',
                'uid'=>['label'=>'操作人','value'=>$model->user_info->username],
                'create_date',
            ],
        ]) ?>
    </div>
</div>
