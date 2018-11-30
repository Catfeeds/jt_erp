<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequisitionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '调拨单管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requisitions-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('新建调拨单', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'order_number',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->order_number, \yii\helpers\Url::to(['update', 'id' => $model->id]));
                    }
                ],
                'order_type',
                'out_stock',
                'in_stock',
                // 'create_date',
                // 'create_uid',
                // 'order_status',

                ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => ' {update} {view}',
                    'headerOptions' => ['width' => '180']
                ]
        ],
        ]); ?>
    </div>
</div>
