<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\grid\EditableColumn;
use kartik\datetime\DateTimePicker;
use kartik\export\ExportMenu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $is_purchase yii\data\ActiveDataProvider */
/* @var $is_custom_service yii\data\ActiveDataProvider */
/* @var $is_select yii\data\ActiveDataProvider */
/* @var $is_leader yii\data\ActiveDataProvider */

$this->title = '订单管理';
$this->params['breadcrumbs'][] = $this->title;
$this->params['is_purchase'] = $is_purchase;
$this->params['is_custom_service'] = $is_custom_service;
$this->params['is_select'] = $is_select;
$this->params['is_leader'] = $is_leader;

if($is_custom_service == 1) {
    $comment= [
        'attribute' => 'comment',
        'class' => 'kartik\grid\EditableColumn',
        'editableOptions' => [
            'asPopover' => false,
            'inputType' => \kartik\editable\Editable::INPUT_TEXTAREA,
            'options' => [
                'rows' => 4,
            ],
        ],
    ];
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'','template' => ' {view}'];


} else {
    $comment = ['attribute' => 'comment','value' => function ($data) {
        return $data->comment;
    }];
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'','template' => ' {view}'];

}
?>
<div class="orders-index box box-primary">
    <div class="box-header with-border">

    </div>
    <div class="box-body table-responsive">
        <?php echo $this->render('_search', ['model' => $searchModel, 'orderTimeBegin' => $orderTimeBegin, 'orderTimeEnd' => $orderTimeEnd, 'groupMember' => $groupMember,'spu'=>$spu,'is_select'=>$is_select]); ?>
        <?php


        if ($this->params['is_purchase'] == 1 && $is_select !=1) {

            echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">订单导出: </div>';

            if ($this->params['is_leader'] == 1)
            {
                echo ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'id',
                        'order_no',
                        'country',
                        [
                            'attribute' => 'product',
                            'value' => function ($data)
                            {
                                $website = \app\models\Websites::findOne($data->website_id);
                                $product = \app\models\ProductsBase::find()->where(['spu' => $website->spu])->one();
                                if ($product)
                                {
                                    return $product->title;
                                }
                            }
                        ],
                        'district',
                        [
                            'attribute' => 'city',
                            'value' => function($data){
                                return preg_replace('/\(.+\)/i', '', $data->city);
                            }
                        ],
                        'weight',
                        'create_date',
                        'qty',
                        'total',
                        [
                            'attribute' => 'status',
                            'label' => '订单状态',
                            'value' => function ($data) {
                                return \app\models\Orders::$status_arr[$data->status];
                            }
                        ],
                        [
                            'attribute' => 'uid',
                            'value' => function ($data) {
                                $user = \app\models\User::findOne($data->uid);
                                return $user->name;
                            }
                        ],
                        

                    ]
                ]);
            }
            else
            {
                echo ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'id',
                        'order_no',
                        [
                            'attribute' => 'website_id',
                            'label' => '广告连接',
                            'value' => function($data){
                                $website = \app\models\Websites::findOne($data->website_id);
                                return 'http://'.$website->domain.'/shop/'.$website->host;
                            }
                        ],
                        [
                            'attribute' => 'product',
                            'value' => function ($data) {
                                $website = \app\models\Websites::findOne($data->website_id);
                                $product = \app\models\ProductsBase::find()->where(['spu' => $website->spu])->one();
                                if ($product) {
                                    return $product->title;
                                }
                            }
                        ],
                        [
                            'attribute' => '英文品名',
                            'value' => function ($data) {
                                $website = \app\models\Websites::findOne($data->website_id);
                                $product = \app\models\ProductsBase::find()->where(['spu' => $website->spu])->one();
                                if ($product) {
                                    return $product->en_name;
                                }
                            }
                        ],
                        [
                            'label' => '订单详情',
                            'format' => 'raw',
                            'value' => function ($data) {
                                $items = $data->getItems($data->id);
                                $html = '';
                                foreach ($items as $sku) {
                                    $product = $sku->getProduct();

                                    if ($product) {
                                        $buyLink = $product->getBuyLink();
                                        if (Yii::$app->user->getId() == 43)
                                        {
                                            $html .= '<tr><td>' . $sku->sku . "\n</td></tr>";
                                        }
                                        else
                                        {
                                            $html .= '<tr><td>' . $sku->sku . ' 颜色: ' . $product->color . ' 尺寸: ' . $product->size . ' 采购链接：' . $buyLink . "\n</td></tr>";
                                        }
                                    }

                                }
                                return '<table>' . $html . '</table>';
                            },
                        ],
                        'name',
                        [
                            'attribute' => 'mobile',
                            'value' => function($data){
                                return "\t".$data->mobile."\t";
                            }
                        ],
                        'email',
                        'country',
                        'district',
                        [
                            'attribute' => 'city',
                            'value' => function($data){
                                return preg_replace('/\(.+\)/i', '', $data->city);
                            }
                        ],
                        [
                            'attribute' => 'area',
                            'value' => function($data){
                                return preg_replace('/\(.+\)/i', '', $data->area);
                            }
                        ],
                        'address',
                        'post_code',
                        'ip',
                        'weight',
                        'create_date',
                        'comment',
                        'qty',
                        'total',
                        [
                            'attribute' => 'status',
                            'label' => '订单状态',
                            'value' => function ($data) {
                                return \app\models\Orders::$status_arr[$data->status];
                            }
                        ],
                        [
                            'attribute' => 'uid',
                            'value' => function ($data) {
                                $user = \app\models\User::findOne($data->uid);
                                return $user->name;
                            }
                        ],
                        'order_no',

                    ]
                ]);
            }


//            if ($this->params['is_leader'] == 0)
//            {
                echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">采购单导出: </div>';

                echo ExportMenu::widget([

                    'dataProvider' => $orderItemData,
                    'columns' => [
                        [
                            'label' => '国家',
                            'value' => function($data){
                                $order = $data->getOrder();
                                return $order->country;
                            }
                        ],
                        'order_id',
                        [
                            'label' => '下单时间',
                            'value' => function($data){
                                $order = $data->getOrder();
                                return $order->create_date;
                            }
                        ],
                        [
                            'label' => '订单状态',
                            'value' => function($data){
                                $order = $data->getOrder();
                                return $order->status_array[$order->status];
                            }
                        ],
                        [
                            'attribute' => '广告投放人员',
                            'value' => function ($data) {
                                $order = $data->getOrder();
                                $user = \app\models\User::findOne($order->uid);
                                return $user->name;
                            }
                        ],
                        [
                            'label' => 'SPU',
                            'value' => function($data){
                                return substr($data->sku, 0, 8);
                            }
                        ],
                        'sku',
                        [
                            'label' => '产品名称',
                            'value' => function($data){
                                $pv = $data->getProduct();
                                if($pv)
                                {
                                    $product = $pv->getProduct();
                                    return $product->title;
                                }

                            }
                        ],
                        [
                            'label' => '颜色',
                            'value' => function($data){
                                $pv = $data->getProduct();
                                if($pv)
                                {
                                    return $pv->color;
                                }

                            }
                        ],
                        [
                            'label' => '尺寸',
                            'value' => function($data){
                                $pv = $data->getProduct();
                                if($pv)
                                {
                                    return $pv->size;
                                }

                            }
                        ],
                        'qty',
                        [
                            'label' => '采购链接',
                            'value' => function($data){
                                $pv = $data->getProduct();
                                if($pv)
                                {
                                    return $pv->getBuyLink();
                                }

                            }
                        ]


                    ]
                ]);
//            }


        }
        ?>
        <?= Html::a('打印拣(备)货单', "javascript:void(0);", ['class' => 'btn btn-success gridviewjhd', 'id' => 'print-top', 'style' => 'margin-left:30px;']) ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => $this->params['is_purchase']  == 1 && $is_select!=1 ? " <div class=\"kv-panel-before\"><div class=\"pull-right\"><div class=\"btn-toolbar kv-grid-toolbar\" role=\"toolbar\">{toolbar}</div></div>    <div class=\"clearfix\"></div></div>\n{items}\n{summary}\n{pager}" : " <div class=\"kv-panel-before\"><div class=\"pull-right\"></div><div class=\"clearfix\"></div></div>\n{items}\n{summary}\n{pager}",
            // set your toolbar
            'exportConfig' => [
                GridView::EXCEL => [
                    'label' => '导出订单',
                    'icon' => $isFa ? 'file-excel-o' : 'floppy-remove',
                    'iconOptions' => ['class' => 'text-success'],
                    'showHeader' => true,
                    'showPageSummary' => true,
                    'showFooter' => true,
                    'showCaption' => true,
                    'filename' => '订单列表' . date("Y-m-d"),
                    'alertMsg' => '确定要导出订单？',
                    'options' => [
                        'title' => '',
                    ],
                    'mime' => 'application/vnd.ms-excel',
                    'config' => [
                        'worksheet' => Yii::t('kvgrid', 'ExportWorksheet'),
                        'cssFile' => ''
                    ]
                ],
            ],
            'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'print'],
            'columns' => [
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'name' => 'id'
                ],
                [
                    'attribute' => 'id',
                    'value' => function ($data) {
                        return Html::a($data['id'], Url::to(['view', 'id' => $data['id']]));
                    },
                    'format' => 'html'
                ],
                'website_id',
                [
                    'label' => '产品名',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $website = $data->getWebsite($data->website_id);
                        $product = \app\models\ProductsBase::find()->where(['spu' => $website->spu])->one();
                        if ($product) {
                            return '<a href="/shop/' . $website->host . '" target="_blank">' . $product->title . '</a>';
                        }
                    }
                ],
                [
                    'label' => '订单详情',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $items = $data->getItems($data->id);
                        $html = '';
                        foreach ($items as $sku) {
                            $product = $sku->getProduct();
                            $buyLink = '';
                                if ($product) {
                                    $buyLink = $product->getBuyLink();
                                    $html .= '<img class="zoom" src="http://admin.kingdomskymall.net' . $sku->image . '" width="50">' . $sku->sku . ' 颜色: ' . $sku->color . ' 尺寸: ' . $sku->size . '<a href="'.$buyLink.'" target="_blank">采购链接</a></li>';
                                }

                        }
                        return '<ol>' . $html . '</ol>';
                    }
                ],
                [
                    'attribute' => 'name',
                    'label' => '收货人',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($this->params['is_custom_service'] == 1) {
                            return $data->name;
                        } else {
                            return '***';

                        }
                    }
                ],
                [
                    'attribute' => 'mobile',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($this->params['is_custom_service'] == 0) {
                            return '***';
                        }
                        $count = $data->countByCl('mobile');
                        if ($count>1) {
                            return $data->mobile . '<br>' . Html::a('订单数：' . $count, Yii::$app->urlManager->createUrl(['orders/index', 'OrdersSearch[mobile]' => $data->mobile]));
                        } else {
                            return $data->mobile;
                        }

                    }
                ],
                //'email:email',
                [
                    'attribute' => 'email',
                    'label' => 'Email',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($this->params['is_custom_service'] == 1) {
                            return Html::a($data->email, 'mailto:' . $data->email);
                        } else {
                            return '***';

                        }
                    }
                ],
                // 'country',
                [
                    'attribute' => 'country',
                    'label' => '国家',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->country;
                    }
                ],
                // 'district',
                [
                    'attribute' => 'district',
                    'label' => '省',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($this->params['is_custom_service'] == 1 || $this->params['is_leader'] == 1) {
                            return $data->district;
                        } else {
                            return '***';

                        }
                    }
                ],
                //'city',
                [
                    'attribute' => 'city',
                    'label' => '市',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($this->params['is_custom_service'] == 1 || $this->params['is_leader'] == 1) {
                            return preg_replace('/\(.+\)/i', '', $data->city);
                        } else {
                            return '***';

                        }
                    }
                ],
                //'area',
                [
                    'attribute' => 'area',
                    'label' => '区',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($this->params['is_custom_service'] == 1) {
                            return preg_replace('/\(.+\)/i', '', $data->area);
                        } else {
                            return '***';

                        }
                    }
                ],
                //'address',
                [
                    'attribute' => 'address',
                    'label' => '收货地址',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($this->params['is_custom_service'] == 1) {
                            return $data->address;
                        } else {
                            return '***';

                        }
                    }
                ],
                //'post_code',
                [
                    'attribute' => 'post_code',
                    'label' => '邮编',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($this->params['is_custom_service'] == 1) {
                            return $data->post_code;
                        } else {
                            return '***';

                        }
                    }
                ],
                'ip',
                'create_date',
                'weight',
                // 'pay',
                [
                    'attribute' => 'lc_number',
                    'label' => '运单号',
                    'format' => 'raw',
                    'value' => function ($data) {
                       return ($this->params['is_custom_service'] == 1 && $data['lc_number'])?$data['lc_number']:'***';
                    }
                ],
                [
                    'attribute' => 'pdf',
                    'label' => '面单链接',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data['pdf'] && $this->params['is_custom_service'] == 1)
                        {
                            return '<a href="'.$data['pdf'].'" target="_blank">面单链接</a>';
                        }
                        else
                        {
                            return "***";
                        }
                    }
                ],
                'comment' => $comment,
                'qty',
                'total',
                [
                    'attribute' => 'status',
                    'label' => '订单状态',
                    'value' => function ($data) {
                        return \app\models\Orders::$status_arr[$data->status];
                    }
                ],
                [
                    'attribute' => 'uid',
                    'value' => function ($data) {
                        $user = \app\models\User::findOne($data->uid);
                        return $user->name;
                    }
                ],
                [
                    'attribute' => 'is_print',
                    'label' => '是否已打印拣(备)货单',
                    'value' => function($data){
                        return $data->is_print ==1 ?'已打印':'未打印';
                    }
                ],
                // 'lc',
                // 'ip',
                // 'shipping_date',
                // 'delivery_date',
                // 'cost',
                // 'channel_type',
                // 'purchase_time',
                // 'back_total',
                // 'cod_fee',
                // 'shipping_fee',
                // 'ads_fee',
                // 'other_fee',
                // 'comment_u:ntext',
                // 'back_date',
                // 'update_time',
                // 'is_lock',
                // 'copy_admin',
                // 'money_status',

                $button_data,
            ],
        ]); ?>
    </div>
</div>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<?php
$this->registerJs(' 
    $("#print-top").on("click", function () {
        var keys = $("#print").yiiGridView("getSelectedRows");
        if (!keys.length) {
            parent.layer.msg(\'请至少先选中一个\');
        } else {
            var url = \'/orders/pick-printjhd?id=\' + keys;
            printJS(url);
            var arr = String(keys).split(",");
            for (var i = 0; i < arr.length; i++) {
                var data = arr[i];
                $("tr[data-key=" + data + "]").children().eq(15).children().html(\'已打印\');
                $("tr[data-key=" + data + "]").children().eq(15).children().attr(\'class\', \'btn-danger btn\');
            }
            var readyUrl = \'/orders/ready-cargo?id=\' + keys;
            // window.open(readyUrl);
            window.location.href = readyUrl;
        }

    });
');
?>
<script>
    var previous = '';

    <?php $this->beginBlock('select_status_js') ?>

    $("#select_status").on('focus', function () {
        previous = this.value;
    });
    <?php $this->endBlock() ?>
    <?php $this->registerJs($this->blocks['select_status_js']); ?>
    function changeStatus( id) {
        var status_value =  $('#select_status').val();
        $.post("/orders/change-status", {orderId: id, status: status_value}, function (data) {
            console.log(data);
        });
    }
    function noChangeStatus() {
        $('#select_status').val(previous);
    }


</script>
<style>
    /**gallery margins**/
    .zoom {
        -webkit-transition: all 0.35s ease-in-out;
        -moz-transition: all 0.35s ease-in-out;
        transition: all 0.35s ease-in-out;
        cursor: -webkit-zoom-in;
        cursor: -moz-zoom-in;
        cursor: zoom-in;
    }

    .zoom:hover,
    .zoom:active,
    .zoom:focus {
        /**adjust scale to desired size,
        add browser prefixes**/
        -ms-transform: scale(2.5);
        -moz-transform: scale(2.5);
        -webkit-transform: scale(2.5);
        -o-transform: scale(2.5);
        transform: scale(2.5);
        position: relative;
        z-index: 1000;
    }

</style>
