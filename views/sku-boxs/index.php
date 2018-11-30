<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SkuBoxsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'SKU对应表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sku-boxs-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('添中SKU对应', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?php
        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'p_sku',
                's_sku',
                [
                    'label' => '产品名称',
                    'attribute' => 'name',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        return $stockModel->skuInfo(substr($model->p_sku, 0, 8), 'name');
                    }
                ],
                [
                    'label' => '规格型号',
                    'attribute' => 'model',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        return $stockModel->skuInfo($model->p_sku, 'model');
                    }
                ],
                'status',
                'create_date',
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
                'p_sku',
                's_sku',
                [
                    'label' => '产品名称',
                    'attribute' => 'name',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        return $stockModel->skuInfo(substr($model->p_sku, 0, 8), 'name');
                    }
                ],
                [
                    'label' => '规格型号',
                    'attribute' => 'model',
                    'value' => function($model){
                        $stockModel = new \app\models\Stocks();
                        return $stockModel->skuInfo($model->p_sku, 'model');
                    }
                ],
                'status',
                'create_date',
                // 'uid',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
