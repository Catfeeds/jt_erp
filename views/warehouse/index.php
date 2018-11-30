<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WarehouseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '仓库管理';
$this->params['breadcrumbs'][] = $this->title;
$button_data = ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => '{add} {view} {update}',
    'buttons' => [
        'add' => function ($url, $model, $key) {
            return  Html::a('<span  class="glyphicon glyphicon-plus"></span>', '/stock-location-area/index?stock_code='.$model->stock_code, ['title' => '添加库区'] ) ;
        },
    ],
    'headerOptions' => ['width' => '180']
];
?>
<div class="warehouse-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('添加仓库', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'stock_name',
                'stock_code',
                'stock_type',
                'create_date',
                // 'uid',
                // 'status',

                $button_data,
            ],
        ]); ?>
    </div>
</div>
