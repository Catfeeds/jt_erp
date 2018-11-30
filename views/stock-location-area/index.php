<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StockLocationAreaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $_GET['stock_code'].' - 库区管理';
$this->params['breadcrumbs'][] = $this->title;
$this->params['stock_code'] = $stock_code;
$button_data = ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => '{add} {view} {update}{delete}',
    'buttons' => [
        'add' => function ($url, $model, $key) {
            return  Html::a('<span  class="glyphicon glyphicon-plus"></span>', '/stock-location-code/index?stock_code='.$model->stock_code.'&area_code='.$model->area_code, ['title' => '添加库区'] ) ;
        },
        'view' => function ($url, $model, $key) {
            return  Html::a('<span  class="glyphicon glyphicon-eye-open"></span>', '/stock-location-area/view?id='.$model->id.'&stock_code='.$this->params['stock_code'], ['title' => '查看库区'] ) ;
        },
        'update' => function ($url, $model, $key) {
            return  Html::a('<span  class="glyphicon glyphicon-pencil"></span>', '/stock-location-area/update?id='.$model->id.'&stock_code='.$this->params['stock_code'], ['title' => '修改库区'] ) ;
        },
                        'delete' => function ($url, $model, $key) {
                            return  Html::a('<span  class="glyphicon glyphicon-trash"></span>', '/stock-location-area/delete?id='.$model->id.'&stock_code='.$this->params['stock_code'], ['title' => '删除库区','aria-label'=>'删除','data-pjax'=>0,'data-confirm'=>'您确定要删除此项吗？','data-method'=>'post'] ) ;
                        },
    ],
    'headerOptions' => ['width' => '180']
];
?>
<div class="stock-location-area-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('增加库区', '/stock-location-area/create?stock_code='.$stock_code, ['class' => 'btn btn-success btn-flat']) ?>
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
                'area_name',
                [
                    'attribute' => 'uid',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $user = \app\models\User::findOne($data->uid);

                        return $user->username;
                    }
                ],
                // 'create_date',

                $button_data,
            ],
        ]); ?>
    </div>
</div>
