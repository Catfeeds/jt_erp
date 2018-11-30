<?php
use kartik\export\ExportMenu;
use yii\grid\GridView;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

$this->title = '采购及SKU库存查询报表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="websites-form box box-primary">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">SKU实时库存</a></li>
        <li><a href="#tab_2" data-toggle="tab">采购成本</a></li>
        <li><a href="#tab_3" data-toggle="tab">采购成本走势图</a></li>
    </ul>
    
    <div class="tab-content">
        <!-- SKU实时库存--start -->
        <div class="tab-pane active" id="tab_1">
            <div class="box-body table-responsive">
                <?php
                    echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">数据导出: </div>';
                    echo ExportMenu::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'header' => '序号'
                            ],
                            'sku',
                            [
                                'attribute' => 'name',
                                'label' => '产品名称',
                                'value' => function($model){
                                    $stockModel = new \app\models\Stocks();
                                    return $stockModel->skuInfo(substr($model->sku, 0, 8), 'name');
                                }
                            ],
                            [
                                'attribute' => 'model',
                                'label' => '规格型号',
                                'value' => function($model){
                                    $stockModel = new \app\models\Stocks();
                                    return $stockModel->skuInfo($model->sku, 'model');
                                }
                            ],
                            [
                                'attribute' => 'stock',
                                'label' => '现有库存'
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
                        ]
                    ]);
                    ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'header' => '序号'
                        ],
                        'sku',
                        [
                            'attribute' => 'name',
                            'label' => '产品名称',
                            'value' => function($model){
                                $stockModel = new \app\models\Stocks();
                                return $stockModel->skuInfo(substr($model->sku, 0, 8), 'name');
                            }
                        ],
                        [
                            'attribute' => 'model',
                            'label' => '规格型号',
                            'value' => function($model){
                                $stockModel = new \app\models\Stocks();
                                return $stockModel->skuInfo($model->sku, 'model');
                            }
                        ],
                        [
                            'attribute' => 'stock',
                            'label' => '现有库存'
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
                    ],
                ]); ?>
            
            </div>
        </div>
        <!-- SKU实时库存--end -->

        <!-- 采购成本--start -->
        <div class="tab-pane" id="tab_2">
            <div class="box-body table-responsive">
                <div class="websites-search">
                    <?php
                    echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">数据导出: </div>';
                    echo ExportMenu::widget([
                        'dataProvider' => $dataPurchases,
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'header' => '序号'
                            ],
                            [
                                'attribute' => 'order_number',
                                'label' => '采购单号'
                            ],
                            'amaount',
                            'create_time'
                        ]
                    ]);
                    ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataPurchases,
                        'filterModel' => $purchasesSearchModel,
                        'layout' => "{items}\n{summary}\n{pager}",
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'header' => '序号'
                            ],
                            [
                                'attribute' => 'order_number',
                                'label' => '采购单号',
                                'format' => 'raw',
                                'value' => function($model){
                                    return Html::a($model->order_number, \yii\helpers\Url::to(['purchases-sku', 'order_number' => $model->order_number]));
                                }
                            ],
                            [
                                'attribute' => 'amaount',
                                'label' => '采购总成本'
                            ],
                            'create_time'
                            
                        ],
                    ]); ?>
                </div>       
            </div>
        </div>
        <!-- 采购成本--end -->

        <!-- 采购成本走势图--start -->
        <div class="tab-pane" id="tab_3">
            <div class="box-body table-responsive">
            <?php
                    echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">数据导出: </div>';
                        echo ExportMenu::widget([
                            'dataProvider' => $dataChart_download,
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                    'header' => '序号'
                                ],
                                [
                                    'label' => '日期',
                                    'attribute' => 'create_time',
                                    'value'=>function($dataChart_download){
                                        return $dataChart_download["create_time"];
                                    }
                                ],
                                [
                                    'label' => '成本',
                                    'attribute' => 'amount',
                                    'value'=>function($dataChart_download){
                                        return $dataChart_download["amount"];
                                    }
                                ],

                            ]
                        ]);
                    ?>

                    <div class="orders-index box box-primary">
                    <div id="main" style="width: 1000px;height:618px;"></div>
                    <script src="/js/echarts.min.js"></script>
                    <script type="text/javascript">
                    var myChart = echarts.init(document.getElementById('main'));
                    var option = {
                        title: {
                            text: '成本'
                        },
                        tooltip : {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'cross',
                                label: {
                                    backgroundColor: '#6a7985'
                                }
                            }
                        },
                        legend: {
                            data:['成本']
                        },
                        toolbox: {
                            feature: {
                                saveAsImage: {}
                            }
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis : [
                            {
                                type : 'category',
                                boundaryGap : false,
                                data : <?php
                                $title = [];
                                foreach ($dataChart as $value) {
                                    $title[] = $value["create_time"];
                                }
                                echo(json_encode($title));
                                ?>
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                name:'成本',
                                type:'line',
                                stack: '总量',
                                label: {
                                    normal: {
                                        show: true,
                                        position: 'top'
                                    }
                                },
                                areaStyle: {normal: {}},
                                data:<?php
                                $values = [];
                                foreach ($dataChart as $value) {
                                    $values[] = $value["amount"];
                                }
                                echo(json_encode($values));
                                ?>
                            }
                        ]
                    };
                    myChart.setOption(option);
                    </script>
                    </div>

            </div>
        </div>
        <!-- 采购成本走势图--end -->            
    </div>
</div>
<script src="/js/jquery-3.3.1.min.js"></script>
<script>
    $(function(){
        var tabNum = sessionStorage.getItem("tabNum");
        $(".nav-tabs li").eq(tabNum).addClass('active').siblings().removeClass('active');
        $(".tab-content .tab-pane").eq(tabNum).show().siblings(".tab-pane").hide();

        $('.nav-tabs li').click(function(){
            var indexNum = $(this).index();
            $(this).addClass('active').siblings().removeClass('active');
			$(".tab-pane").eq(indexNum).show().siblings(".tab-pane").hide();
            sessionStorage.setItem("tabNum", indexNum);
        });
    })
</script>