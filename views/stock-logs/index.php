<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StockLogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $country yii\data\ActiveDataProvider */

$this->title = '出入库记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-logs-index box box-primary">

    <div class="box-body table-responsive">
        <?php echo $this->render('_search', ['model' => $searchModel,'country'=>$country]); ?>
        <?php
        echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">日志导出: </div>';

        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                [

                    'label' => '订单ID',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return "\t".$data->order_id;
                    }
                ],
                [

                    'label' => '国家',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $info = \app\models\Orders::find()->select('country')->where(array('id'=>$data->order_id))->one();
                        return \app\models\Websites::$country_array[$info['country']];
                    }
                ],
                'sku',
                [
                    'label' => '产品名称',
                    'attribute' => 'name',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        return $stockModel->skuInfo(substr($model->sku, 0, 8), 'name');
                    }
                ],
                [
                    'label' => '规格型号',
                    'attribute' => 'model',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        return $stockModel->skuInfo($model->sku, 'model');
                    }
                ],
                // 'qty',
                [
                    'label' => '数量',
                    'attribute' => 'qty'
                ],
                [
                    'label' => '原数量',
                    'attribute' => 'original_qty'
                ],
                //'cost',
                [
                    'label' => '操作人',
                    'attribute' => 'uid',
                    'value' => function ($data) {
                        $user = \app\models\User::findOne($data->uid);
                        return $user->name;
                    }
                ],
                [
                    'label' => '类型',
                    'attribute' => 'type',
                    'value' => function($model){
                        return $model->status_array[$model->type];
                    },

                ],
                // 'create_date',
                [
                    'label' => '创建时间',
                    'attribute' => 'create_date'
                ],
            ]
            ]);
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'label' => '订单编号',
                    'attribute' => 'order_id',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->order_id;
                    }
                ],
                [
                    'label' => '国家',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $info = \app\models\Orders::find()->select('country')->where(array('id'=>$data->order_id))->one();
                        return \app\models\Websites::$country_array[$info['country']];
                    }
                ],
                'sku',
                [
                    'label' => '产品名称',
                    'attribute' => 'name',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        return $stockModel->skuInfo(substr($model->sku, 0, 8), 'name');
                    }
                ],
                [
                    'label' => '规格型号',
                    'attribute' => 'model',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        return $stockModel->skuInfo($model->sku, 'model');
                    }
                ],
                // 'qty',
                [
                    'label' => '数量',
                    'attribute' => 'qty'
                ],
                [
                    'label' => '原数量',
                    'attribute' => 'original_qty'
                ],
                //'cost',
                [
                    'label' => '操作人',
                    'attribute' => 'uid',
                    'value' => function ($data) {
                        $user = \app\models\User::findOne($data->uid);
                        return $user->name;
                    }
                ],
                [
                    'label' => '类型',
                    'attribute' => 'type',
                    'value' => function($model){
                        return $model->status_array[$model->type];
                    },
                    'filter' => [
                        0 => '采购入库',
                        1 => '订单出库',
                        2 => '调拨出库',
                        3 => '采购退货出库',
                        4 => '订单取消入库',
                        5 => '盘点入库'
                    ]
                ],
                // 'create_date',
                [
                    'label' => '创建时间',
                    'attribute' => 'create_date'
                ],

               // ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
