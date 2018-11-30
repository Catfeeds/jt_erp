<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InventorysSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user_arr yii\data\ActiveDataProvider */

$this->title = '物流状态';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shipping-logistics-state-index box box-primary">
    <div class="box-header with-border">
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/shipping-logistics-state/import-all">导入物流状态</a></div>
    </div>
    <div class="box-body table-responsive">
        <?php
        echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">数据导出: </div>';
        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label' => 'ID',
                    'value' => function($model){
                        return $model->id;
                    }
                ],
                'id_order',
                'lc_number',
                [
                    'attribute' => 'country',
                    'options' => ['style' => 'width:130px'],
                    'value' => function($model){
                        return \app\models\Websites::$country_array[$model->country];
                    },
                ],
                'lc',
                [
                    'attribute' => 'state',
                    'options' => ['style' => 'width:130px'],
                    'value' => function($model){
                        return \app\models\ShippingLogisticsState::$state_arr[$model->state];
                    },
                ],
                [
                    'attribute' => 'type',
                    'options' => ['style' => 'width:130px'],
                    'value' => function($model){
                        return \app\models\ShippingLogisticsState::$type_arr[$model->type];
                    },
                ],
                [
                    'label' => '创建时间',
                    'value' => function($model){
                        return $model->created_at;
                    }
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
        <div class="box-body table-responsive no-padding">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'label' => 'ID',
                        'format' => 'html',
                        'value' => function($model){
                            return $model->id;
                        }
                    ],
                    'id_order',
                    'lc_number',
                    [
                        'attribute' => 'country',
                        'options' => ['style' => 'width:130px'],
                        'value' => function($model){
                            return \app\models\Websites::$country_array[$model->country];
                        },
                        'filter' => \app\models\Websites::$country_array,
                    ],
                    'lc',
                    [
                        'attribute' => 'state',
                        'options' => ['style' => 'width:130px'],
                        'value' => function($model){
                            return \app\models\ShippingLogisticsState::$state_arr[$model->state];
                        },
                        'filter' => \app\models\ShippingLogisticsState::$state_arr,
                    ],
                    [
                        'attribute' => 'type',
                        'options' => ['style' => 'width:130px'],
                        'value' => function($model){
                            return \app\models\ShippingLogisticsState::$type_arr[$model->type];
                        },
                        'filter' => \app\models\ShippingLogisticsState::$type_arr,
                    ],
                    [
                        'label' => '创建时间',
                        'value' => function($model){
                            return $model->created_at;
                        }
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
</div>
