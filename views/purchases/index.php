<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;


/* @var $this yii\web\View */
/* @var $searchModel app\models\PurchasesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '采购单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchases-index box box-primary">

    <div class="box-header with-border">
        <a class="btn btn-primary btn-flat" href="/purchases/new-order">新建采购单</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="btn btn-primary btn-flat" href="/purchases/select-sku">在途数查询</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="btn btn-primary btn-flat" href="/purchases/purchase-prescription">采购时效导出</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="btn btn-primary btn-flat" href="/purchases/purchase-abnormal">采购异常单导出</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <?php
        echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">数据导出: </div>';

        echo ExportMenu::widget([
            'dataProvider' => $itemProvider,
            'columns' => [
                'id',
                [
                    'attribute' => '采购单号',
                    'value' => function($model){
                        return ' '.$model->purchaes_info->order_number;
                    },

                ],
                [
                    'attribute' => '总价',
                    'value' => function($model){
                        return $model->purchaes_info->amaount;
                    },

                ],
                [
                    'attribute' => '采购状态',
                    'value' => function($model){
                        return $model->purchaes_info->status_array[$model->purchaes_info->status];
                    },
                    'filter' => [
                        '草稿',
                        '已确认',
                        '已采购',
                        '退款',
                        '异常',
                        '收货中',
                    ]
                ],
                [
                    'attribute' => '供应商',
                    'value' => function($model){
                        return $model->purchaes_info->supplier;
                    },

                ],
                [
                    'attribute' => '采购平台',
                    'value' => function($model){
                        return $model->purchaes_info->platform;
                    },

                ],
                [
                    'attribute' => '平台订单号',
                    'value' => function($model){
                        return ' '.$model->purchaes_info->platform_order;
                    },

                ],
                [
                    'attribute' => '物流单号',
                    'value' => function($model){
                        return ' '.$model->purchaes_info->platform_track;
                    },

                ],
                [
                    'attribute' => 'sku',
                    'value' => function($model){
                        return $model->sku;
                    },
                ],
                [
                    'label' => '产品名',
                    'value' => function($model){
                        $title =  \app\models\ProductsBase::find()->select('title')->where(array('spu'=>substr($model->sku,0,8)))->one();
                        return $title['title'];
                    },

                ],
                [
                    'attribute' => '数量',
                    'value' => function($model){
                        return $model->qty;
                    },
                ],
                [
                    'attribute' => '实收数量',
                    'value' => function($model){
                        return $model->delivery_qty;
                    },
                ],
                [
                    'attribute' => '颜色',
                    'value' => function($model){
                        return $model->sku_info->color;
                    },

                ],
                [
                    'attribute' => '尺寸',
                    'value' => function($model){
                        return $model->sku_info->size;
                    },

                ],
                [
                    'attribute' => '预计到货时间',
                    'value' => function($model){
                        return $model->purchaes_info->delivery_time;
                    },

                ],
                [
                    'attribute' => '添加时间',
                    'value' => function($model){
                        return $model->purchaes_info->create_time;
                    },

                ]



            ]
        ]);
        ?>
    </div>
    <div class="box-body table-responsive">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                [
                    'attribute' => 'order_number',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->order_number, \yii\helpers\Url::to(['view', 'id' => $model->id]));
                    }
                ],

                'supplier',

                // 'uid',
                 'platform',
                 'platform_order',
//                'track_name',
                 'platform_track',
                [
                    'attribute' => 'amaount',
                    'options' => ['style' => 'width:120px'],
                    'value' => function($model) {
                        return $model->amaount;
                    }
                ],
                [
                    'attribute' => 'status',
                    'options' => ['style' => 'width:120px'],
                    'value' => function($model){
                        return $model->status_array[$model->status];
                    },
                    'filter' => [
                        '草稿',
                        '已确认',
                        '已采购',
                        '已入库',
                        '退款',
                        '异常',
                        '收货中',
                    ]
                ],
                [
                    'label' => 'SPU',
                    'attribute' => 'spu',
                    'options' => ['style' => 'width:130px'],
                    'value' => function($model) {
                        $itemModel = new \app\models\PurchasesItems();
                        $item = $itemModel->find()->where(['purchase_number' => $model->order_number])->asArray()->all();

                        $spu = [];
                        foreach ($item as $row) {
                            $spu[mb_substr($row['sku'], 0, 8, 'utf-8')] = mb_substr($row['sku'], 0, 8, 'utf-8');
                        }
                        $spu = array_values($spu);
                        //implode(',', $spu);
                        $res = $spu[0];
                        if(count($spu) > 1) {
                            $res .= '...';
                        }
                        return $res;
                    }
                ],
                [
                    'label' => '产品名',
                    'value' => function($model) {
                        $itemModel = new \app\models\PurchasesItems();
                        $item = $itemModel->find()->where(['purchase_number' => $model->order_number])->asArray()->all();

                        $spu = [];
                        foreach ($item as $row) {
                            $spu[mb_substr($row['sku'], 0, 8, 'utf-8')] = mb_substr($row['sku'], 0, 8, 'utf-8');
                        }
                        $spu = array_values($spu);
                        $title = \app\models\ProductsBase::find()->select('title')->where(array('spu'=>$spu[0]))->one();
                        $res = $title['title'];
                        if(count($spu) > 1) {
                            $res .= '...';
                        }
                        return $res;
                    }
                ],
                [
                    'attribute' => 'delivery_time',
                    'options' => ['style' => 'width:120px'],
                    'value' => function($model) {
                       return $model->delivery_time;
                    }
                ],
//                'delivery_time',
                'create_time',
                ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => '{update} {view} {add}',
                    'headerOptions' => ['width' => '180'],
                    'buttons' => [
                        'add' => function ($url, $model, $key) {
                            if (in_array($model->status, [2,3,5,6]) && $model->is_back != 1) {
                                return Html::a('<span class="glyphicon glyphicon-arrow-right  btn btn-warning btn-sm">采购退货</span>', 'javascript:void(0);', ['title' => '添加', 'onclick' => 'backPurchases('.$model->id.',"'.$model->order_number.'","'.$model->status_array[$model->status].'")'] );
                            }
                            if($model->is_back == 1) {
                                $back = \app\models\Back::find()->where(['order_number' => $model->order_number])->select('id')->one();
                                return Html::a('<span class="glyphicon glyphicon-arrow-right  btn btn-primary btn-sm">有退货</span>', '/back/view?id='.$back->id, ['title' => '添加'] );
                            }
                        },
                    ],
                ]
            ],
        ]); ?>
    </div>
</div>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . "/js/layer/layer.js", ['depends' => ['app\assets\AppAsset']]); ?>
<script>
    function backPurchases(id, order_number, status)
    {
        layer.open({
            type: 2,
            title: '采购退货-采购单号:'+order_number+ '-' + status,
            area: ['980px', '580px'], //宽高
            maxmin: true,
            // shadeClose: true,
            content: 'back?id='+id
        });
    }
</script>

