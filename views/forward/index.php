<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StockLogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $country yii\data\ActiveDataProvider */
/* @var $add_time_begin yii\data\ActiveDataProvider */
/* @var $add_time_end yii\data\ActiveDataProvider */
/* @var $forward_time_begin yii\data\ActiveDataProvider */
/* @var $forward_time_end yii\data\ActiveDataProvider */

$this->title = '转寄仓订单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-logs-index box box-primary">

    <div class="box-body table-responsive">
        <?php echo $this->render('_search', ['model' => $searchModel,'add_time_begin' =>$add_time_begin,'add_time_end' =>$add_time_end,'forward_time_begin' =>$forward_time_begin,'forward_time_end' =>$forward_time_end]); ?>
        <?php
        echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">转寄仓订单导出: </div>';

        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                [
                    'label' => '订单编号',
                    'format' => 'raw',
                    'value' => function ($data)
                    {
                        return $data->id_order;
                    }
                ],
                [
                    'label' => '转寄仓库',
                    'format' => 'raw',
                    'value' => function ($data)
                    {
                        $info = \app\models\Warehouse::find()->select('stock_name')->where(array('stock_code'=>$data->stock_code))->one();
                        return $info['stock_name'];
                    }
                ],
                'country',
                'lc_number',
                [
                    'attribute' => 'status',
                    'value' => function($model)
                    {
                        return \app\models\Forward::$status_arr[$model->status];
                    },
                    'filter' => \app\models\Forward::$status_arr,
                ],
                'new_id_order',
                'new_lc_number',
                'add_time',
                'forward_time',
            ]
        ]);
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => '订单号',
                    'format' => 'raw',
                    'value' => function ($data)
                    {
                        return $data->id_order;
                    }
                ],
                [
                    'label' => '转寄仓库',
                    'format' => 'raw',
                    'value' => function ($data)
                    {
                        $info = \app\models\Warehouse::find()->select('stock_name')->where(array('stock_code'=>$data->stock_code))->one();
                        return $info['stock_name'];
                    }
                ],
                'country',
                'lc_number',
                [
                    'attribute' => 'status',
                    'value' => function($model)
                    {
                        return \app\models\Forward::$status_arr[$model->status];
                    },
                    'filter' => ['' => '请选择'] + \app\models\Forward::$status_arr,
                ],
                'new_id_order',
                'new_lc_number',
                [
                    'label' => '创建时间',
                    'format' => 'raw',
                    'value' => function ($data)
                    {
                        return $data['add_time'];
                    }
                ],
                [
                    'label' => '匹配转寄时间',
                    'format' => 'raw',
                    'value' => function ($data)
                    {
                        return $data['forward_time'];
                    }
                ]
            ],
        ]); ?>
    </div>
</div>
