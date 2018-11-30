<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Warehouse */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Warehouses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="warehouse-view box box-primary">
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
                'stock_name',
                'stock_code',
                'stock_type'=>[
                    'attribute' => 'stock_type',
                    'value' => $model->stock_type == 1 ? '普通' : '转运仓'
                ],
                'create_date',
                'uid'=>['label'=>'操作人','value'=>$model->user_info->username],
                'status'=>[
                    'attribute' => 'status',
                    'value' => $model->status == 1 ? '可用' : '禁用'
                ],
            ],
        ]) ?>
    </div>
</div>
