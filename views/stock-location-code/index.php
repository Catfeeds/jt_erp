<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StockLocationCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $_GET['stock_code'].' - 库位管理';
$this->params['breadcrumbs'][] = $this->title;
$this->params['stock_code'] = $stock_code;
$this->params['area_code'] = $area_code;
$button_data = ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => '{view} {update}{delete}',
    'buttons' => [
        'view' => function ($url, $model, $key) {
            return  Html::a('<span  class="glyphicon glyphicon-eye-open"></span>', '/stock-location-code/view?id='.$model->id.'&stock_code='.$this->params['stock_code'].'&area_code='.$this->params['area_code'], ['title' => '查看库区'] ) ;
        },
        'update' => function ($url, $model, $key) {
            return  Html::a('<span  class="glyphicon glyphicon-pencil"></span>', '/stock-location-code/update?id='.$model->id.'&stock_code='.$this->params['stock_code'].'&area_code='.$this->params['area_code'], ['title' => '修改库区'] ) ;
        },
        'delete' => function ($url, $model, $key) {
            return  Html::a('<span  class="glyphicon glyphicon-trash"></span>', '/stock-location-code/delete?id='.$model->id.'&stock_code='.$this->params['stock_code'].'&area_code='.$this->params['area_code'], ['title' => '删除库区','aria-label'=>'删除','data-pjax'=>0,'data-confirm'=>'您确定要删除此项吗？','data-method'=>'post'] ) ;
        },
    ],
    'headerOptions' => ['width' => '180']
];
?>
<div class="stock-location-code-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('增加库位', '/stock-location-code/create?stock_code='.$stock_code.'&area_code='.$area_code, ['class' => 'btn btn-success btn-flat']) ?>
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
                'stock_code',
                'area_code',
                'code',
                'info',
                // 'uid',
                // 'create_date',

                $button_data,
            ],
        ]); ?>
    </div>
</div>
