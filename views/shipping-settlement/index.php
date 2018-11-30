<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InventorysSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user_arr yii\data\ActiveDataProvider */

$this->title = '物流结算';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventorys-index box box-primary">
    <div class="box-header with-border">
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/shipping-settlement/import-settlement">物流结算导入</a></div>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/shipping-order-settlement/index">物流订单结算列表</a></div>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php
        echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">结算数据导出: </div>';
        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'settlement_number',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->settlement_number, \yii\helpers\Url::to(['view', 'id' => $model->id]));
                    }
                ],
                'lc',
                [
                    'label' => '回款总金额',
                    'value' => function($model){
                        return $model->back_total;
                    },
                ],
                [
                    'label' => '其他费用',
                    'value' => function($model){
                        return $model->other_fee;
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
                        return \app\models\ShippingSettlement::$status_arr[$model->status];
                    },
                    'filter' => [''=>'所有']+\app\models\ShippingSettlement::$status_arr,
                ],
                [
                    'label' => '操作人',
                    'value' => function($model){
                        $user = \app\models\User::findOne($model->uid);
                        return $user->name;
                    },
                    'filter' => [''=>'所有']+$user_arr
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
                    'attribute' => 'settlement_number',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->settlement_number, \yii\helpers\Url::to(['view', 'id' => $model->id]));
                    }
                ],
                'lc',
                [
                    'label' => '回款总金额',
                    'value' => function($model){
                        return $model->back_total;
                    },
                ],
                [
                    'label' => '其他费用',
                    'value' => function($model){
                        return $model->other_fee;
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
                        return \app\models\ShippingSettlement::$status_arr[$model->status];
                    },
                    'filter' => [''=>'所有']+\app\models\ShippingSettlement::$status_arr,
                ],
                [
                    'label' => '操作人',
                    'value' => function($model){
                        $user = \app\models\User::findOne($model->uid);
                        return $user->name;
                    },
                    'filter' => [''=>'所有']+$user_arr
                ],
                [
                    'label' => '回款时间',
                    'value' => function($model){
                        return $model->date_time;
                    },
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
