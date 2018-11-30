<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\grid\EditableColumn;
use kartik\datetime\DateTimePicker;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderStatusChangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $orderTimeBegin yii\data\ActiveDataProvider */
/* @var $orderTimeBegin yii\data\ActiveDataProvider */
/* @var $time yii\data\ActiveDataProvider */
/* @var $country yii\data\ActiveDataProvider */
/* @var $status yii\data\ActiveDataProvider */

$this->title = '订单统计报表';
$this->params['breadcrumbs'][] = $this->title;
$button_data = ['class' => 'yii\grid\ActionColumn','header'=>'查看操作记录','template' => ' {view}'];
?>
<div class="orders-index box box-primary">
    <div class="box-header with-border">

    </div>
    <div class="box-body table-responsive">
        <?php echo $this->render('_search', ['model' => $searchModel, 'orderTimeBegin' => $orderTimeBegin, 'orderTimeEnd' => $orderTimeBegin, 'time' => $time, 'country' => $country, 'status' => $status]); ?>

        <?php
        echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">订单统计导出: </div>';

        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'id_order',
                'create_at',
                [
                    'attribute' => 'confirm_at',
                    'value' => function ($data) {
                        return $data->confirm_at?$data->confirm_at:0;
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'cancel_at',
                    'value' => function ($data) {
                        return $data->cancel_at?$data->cancel_at:0;
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'purchasing_at',
                    'value' => function ($data) {
                        return $data->purchasing_at?$data->purchasing_at:0;
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'purchase_at',
                    'value' => function ($data) {
                        return $data->purchase_at?$data->purchase_at:0;
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'sending_at',
                    'value' => function ($data) {
                        return $data->sending_at?$data->sending_at:0;
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'send_at',
                    'value' => function ($data) {
                        return $data->send_at?$data->send_at:0;
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'receive_at',
                    'value' => function ($data) {
                        return $data->receive_at?$data->receive_at:0;
                    },
                    'format' => 'html'
                ],
            ]
        ]);
        ?>

        <?= GridView::widget([

            'dataProvider' => $dataProvider,

            'columns' => [
                'id',
                'id_order',
                'create_at',
                [
                    'attribute' => 'confirm_at',
                    'value' => function ($data) {
                        return $data->confirm_at?$data->confirm_at.' h':'***';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'cancel_at',
                    'value' => function ($data) {
                        return $data->cancel_at?$data->cancel_at.' h':'***';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'purchasing_at',
                    'value' => function ($data) {
                        return $data->purchasing_at?$data->purchasing_at.' h':'***';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'purchase_at',
                    'value' => function ($data) {
                        return $data->purchase_at?$data->purchase_at.' h':'***';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'sending_at',
                    'value' => function ($data) {
                        return $data->sending_at?$data->sending_at.' h':'***';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'send_at',
                    'value' => function ($data) {
                        return $data->send_at?$data->send_at.' h':'***';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'receive_at',
                    'value' => function ($data) {
                        return $data->receive_at?$data->receive_at.' h':'***';
                    },
                    'format' => 'html'
                ],
                $button_data,
            ],
        ]); ?>
    </div>
</div>
