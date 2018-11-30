<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StocksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stocks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stocks-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Stocks', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/stocks/import-stocks">库存批量更新</a></div>

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
                        return $model->lockStock($model->sku);
                    }
                ],
                'cost',
                // 'uid',
                // 'create_date',
                // 'update_date',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
