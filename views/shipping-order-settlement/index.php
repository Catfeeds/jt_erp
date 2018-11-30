<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InventorysSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user_arr yii\data\ActiveDataProvider */

$this->title = '物流订单结算';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventorys-index box box-primary">
    <div class="box-header with-border">
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/shipping-settlement/index">物流结算列表</a></div>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php
        echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">订单结算数据导出: </div>';
        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'id_order',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->id_order, \yii\helpers\Url::to(['view', 'id' => $model->id_order]));
                    }
                ],
                'lc_number',
                [
                    'label' => '回款总金额',
                    'value' => function($model){
                        return $model->back_order_total;
                    },
                ],
                [
                    'label' => '货币',
                    'value' => function($model){
                        return $model->currency;
                    },
                ],
                [
                    'attribute' => 'status',
                    'value' => function($model){
                        return \app\models\ShippingOrderSettlement::$status_arr[$model->status];
                    },
                    'filter' => [''=>'所有']+\app\models\ShippingOrderSettlement::$status_arr,
                ],
                [
                    'label' => '创建时间',
                    'value' => function($model){
                        return $model->created_at;
                    },
                ],
                [
                    'label' => '更新时间',
                    'value' => function($model){
                        return $model->update_at;
                    }
                ],
            ],
        ]);
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'id_order',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->id_order, \yii\helpers\Url::to(['view', 'id' => $model->id_order]));
                    }
                ],
                'lc_number',
                [
                    'label' => '回款总金额',
                    'value' => function($model){
                        return $model->back_order_total;
                    },
                ],
                [
                    'label' => '回款金额',
                    'value' => function($model){
                        return $model->back_order;
                    },
                ],
                [
                    'label' => '费用',
                    'value' => function($model){
                        return $model->back_order_total-$model->back_order;
                    },
                ],
                [
                    'label' => '货币',
                    'value' => function($model){
                        return $model->currency;
                    },
                ],
                [
                    'attribute' => 'status',
                    'value' => function($model){
                        return \app\models\ShippingOrderSettlement::$status_arr[$model->status];
                    },
                    'filter' => [''=>'所有']+\app\models\ShippingOrderSettlement::$status_arr,
                ],
                [
                    'label' => '创建时间',
                    'value' => function($model){
                        return $model->created_at;
                    },
                ],
                [
                    'label' => '更新时间',
                    'value' => function($model){
                        return $model->update_at;
                    }
                ],
            ],
        ]); ?>
    </div>
</div>
