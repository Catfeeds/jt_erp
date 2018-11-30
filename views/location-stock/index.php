<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LocationStockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '库位库存';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="location-stock-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('添加库存', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?php
        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'stock_code',
                'area_code',
                'location_code',
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
                'stock',
                [
                    'label' => '待采购数量',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        $purchasing = $stockModel->repTransBySku($model->sku);
                        return $purchasing?$purchasing:0;
                    }
                ],
                [
                    'label' => '在途库存',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        $road_num = $stockModel->transitInventoryBySku($model->sku);
                        return $road_num?$road_num:0;
                    }
                ],
                [
                    'label' => '锁定库存',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        $lock_num = $stockModel->lockStock($model->sku);
                        return $lock_num?$lock_num:0;
                    }
                ],
            ]
        ]);
        ?>
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
                'location_code',
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
                 'stock',
                [
                    'label' => '锁定库存',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        $lock_num = $stockModel->lockStock($model->sku);
                        return $lock_num?$lock_num:0;
                    }
                ],
                [
                    'label' => '待采购数量',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        $purchasing = $stockModel->repTransBySku($model->sku);
                        return $purchasing?$purchasing:0;
                    }
                ],
                [
                    'label' => '在途库存',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        $road_num = $stockModel->transitInventoryBySku($model->sku);
                        return $road_num?$road_num:0;
                    }
                ],
                // 'create_date',
                // 'update_date',

                ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => '{add} {view}'],
            ],
        ]); ?>
    </div>
</div>
