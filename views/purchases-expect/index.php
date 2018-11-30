<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\grid\EditableColumn;
use kartik\datetime\DateTimePicker;
use kartik\export\ExportMenu;

$this->title = '预计到货统计报表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index box box-primary">
    <div class="box-header with-border">

    </div>
    <div class="box-body table-responsive">
        <?php echo $this->render('_search', ['model' => $searchModel, 'delivery_time' => $delivery_time]); ?>

        <?php
        echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">预计到货统计导出: </div>';

        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                [
                    'label' => '预计到货时间',
                    'attribute' => 'delivery_time',
                ],
                'order_number',
                [
                    'label' => 'SPU',
                    'value' => function($model){
                        $stockModel = new \app\models\PurchasesExpectSearch();
                        return $stockModel->skuCode($model->order_number);
                    }
                ],
                [
                    'attribute' => 'SPU数量',
                    'value' => function($model){
                        $stockModel = new \app\models\PurchasesExpectSearch();
                        return $stockModel->skuQty($model->order_number);
                    }
                ],
                'supplier',
                'platform',
                [
                    'label' => '商品名称',
                        'value' => function($model){
                        $Model = new \app\models\PurchasesExpectSearch();
                        return $Model->skuName($model->order_number);
                    }
                ],
                [
                    'label' => '状态',
                    'value' => function($model){
                                return $model->status_array[$model->status];
                            }
                ],
            ]
        ]);
        ?>

        <?= 
        GridView::widget([

            'dataProvider' => $dataProvider,

            'columns' => [
                [
                    'label' => '预计到货时间',
                    'attribute' => 'delivery_time',
                ],
                'order_number',
                [
                    'label' => 'SPU',
                    'value' => function($model){
                        $stockModel = new \app\models\PurchasesExpectSearch();
                        return $stockModel->skuCode($model->order_number);
                    }
                ],
                [
                    'label' => 'SPU数量',
                    'value' => function($model){
                        $stockModel = new \app\models\PurchasesExpectSearch();
                        return $stockModel->skuQty($model->order_number);
                    }
                ],
                'supplier',
                'platform',
                [
                    'label' => '商品名称',
                        'value' => function($model){
                        $Model = new \app\models\PurchasesExpectSearch();
                        return $Model->skuName($model->order_number);
                    }
                ],
                [
                    'label' => '状态',
                    'value' => function($model){
                                return $model->status_array[$model->status];
                            }
                ],
            ],
        ]); 
        ?>
    </div>
</div>
