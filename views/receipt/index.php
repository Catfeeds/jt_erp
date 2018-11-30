<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PurchasesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '入库单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchases-index box box-primary">

    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}{summary}{pager}",
            'columns' => [
                [
                        'attribute' => 'order_number',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->order_number, \yii\helpers\Url::to(['update', 'id' => $model->id]));
                    }
                ],

                'supplier',

                // 'uid',
                 'platform',
                 'platform_order',
                'track_name',
                 [
                         'attribute' => 'platform_track',
                     'format' => 'raw',
                     'value' => function($model){
                        return Html::a($model->platform_track, Url::to(['track', 'track' => $model->platform_track]));
                     }
                 ],
                [
                    'attribute' => 'status',
                    'value' => function($model){
                        return $model->status_array[$model->status];
                    },
                    'filter' => [
                        '草稿',
                        '已确认',
                        '已采购',
                        '已入库',
                        '退款',
                        '异常',
                        '收货中'
                    ]
                ],
                'create_time',

                ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => ' {update} ',
                    'headerOptions' => ['width' => '50']
                ]
            ],
        ]); ?>
    </div>
</div>
