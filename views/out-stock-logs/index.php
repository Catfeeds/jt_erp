<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StockLogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '出库记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-logs-index box box-primary">

    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'sku',
                'order_id',
                'qty',
                'cost',
                [
                    'attribute' => 'uid',
                    'value' => function ($data) {
                        $user = \app\models\User::findOne($data->uid);
                        return $user->name;
                    }
                ],
                [
                    'attribute' => 'log_type',
                    'value' => function($model){
                        return $model->status_array[$model->log_type];
                    },
                    'filter' => [
                        //1=>'采购入库',
                        2=>'销售出库',
                        //3=>'调拨入库',
                        4=>'调拨出库'
                    ]
                ],
                 'create_date',

               // ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
