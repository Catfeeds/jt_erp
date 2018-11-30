<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\grid\EditableColumn;
use kartik\datetime\DateTimePicker;
use kartik\export\ExportMenu;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单审核';
$this->params['breadcrumbs'][] = $this->title;
$this->params['is_purchase'] = $is_purchase;
$this->params['is_custom_service'] = $is_custom_service;
$this->params['is_select'] = $is_select;

if($is_select != 1) {
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
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'','template' => ' {view} {update}'];


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
                        'label' => '订单详情',
                    'format' => 'raw',
                        'value' => function ($data) {
                            $items = $data->getItems($data->id);
                            $html = '';
                            foreach ($items as $sku) {
                                $product = $sku->getProduct();

                                if ($product) {
                                    $buyLink = $product->getBuyLink();
                                    $html .= '<tr><td>' . $sku->sku . ' 颜色: ' . $product->color . ' 尺寸: ' . $product->size . ' 采购链接：' . $buyLink . "\n</td></tr>";
                                }

                            }
                            return '<table>' . $html . '</table>';
                        },
                    ],
                    'name',
                    'mobile',
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

                ]
            ]);


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


        }
        ?>
        <button type="submit" class="btn btn-primary check-stock" style="margin: 10px;">更新待发货</button>
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
            'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'select'],
            'columns' => [
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'name' => 'id'
                ],
                'id',
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
                                    $html .= '<img class="zoom" src="http://admin.kingdomskymall.net' . $sku->image . '" width="50">' . $sku->sku . ' 颜色: ' . $product->color . ' 尺寸: ' . $product->size . '<a href="'.$buyLink.'" target="_blank">采购链接</a></li>';
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
                            return $data->mobile . '<br>' . Html::a('订单数：' . $count, Yii::$app->urlManager->createUrl(['orders-audit/index', 'OrdersSearch[mobile]' => $data->mobile]));
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
                        if ($this->params['is_custom_service'] == 1) {
                            return $data->country;
                        } else {
                            return '***';

                        }
                    }
                ],
                // 'district',
                [
                    'attribute' => 'district',
                    'label' => '省',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($this->params['is_custom_service'] == 1) {
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
                        if ($this->params['is_custom_service'] == 1) {
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
                // 'pay',

                'comment' => $comment,
                'qty',
                'total',
                [
                    'attribute' => 'status',
                    'label' => '订单状态', // 状态 1待确认 2已经确认 3已采购 4已发货 5签收 6拒签 7已入库-8已打包-9已回款
                    'format' => 'raw',
                    'filter' => \app\models\Orders::$status_arr,
                    'value' => function ($data) {
                        $status = \app\models\Orders::$status_arr;
                        $status_arr = array('2'=>'已确认','10'=>'已取消');
                        $select = "<select name='select_status_".$data->id."' onchange='krajeeDialog.confirm(\"是否修改订单状态？\", function(result){if(result){changeStatus(" . $data->id . ")}else{noChangeStatus();}})'>";
                        $show_status = '';
                        $select .= "<option value='$data->status' >".$status[$data->status]."</option>";
                        if (in_array($data->status,array(1,2)))
                        {
                            foreach ($status_arr as $k => $v) {
                                if ($data->status != $k)
                                {
                                    $select .= "<option value='$k'>$v</option>";
                                }
                            }
                        }
                        elseif ($data->status == 10)
                        {
                            $select .= "<option value='2'>已确认</option>";
                        }
                        elseif ($data->status == 12)
                        {
                            $select .= "<option value='10'>已取消</option>";
                            $select .= "<option value='7'>待发货</option>";
                        }
                        if ($this->params['is_custom_service'] == 0 || $this->params['is_select'] == 1) {
                            return $show_status;
                        }
                        $select .= "</select>";
                        return $select;
                    }
                ],
                [
                    'attribute' => 'uid',
                    'value' => function ($data) {
                        $user = \app\models\User::findOne($data->uid);
                        return $user->name;
                    }
                ],
                // 'lc',
                // 'lc_number',
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
                ['class' => 'yii\grid\ActionColumn','template' => '{cancle_order} {edit_order}',
                    'headerOptions' => ['width' => '180'],
                    'buttons' => [
                        'cancle_order' => function ($urk, $model, $key) {
                            // 客服取消的权限
                            $user_id = Yii::$app->user->id;
                            $Model = new User();
                            $user_list = $Model->find()
                                        ->select('auth_assignment.item_name')
                                        ->leftJoin('auth_assignment','auth_assignment.user_id = user.id')
                                        ->where('user.id = '.$user_id)
                                        ->asArray()
                                        ->one();
                            if (!in_array($model->status, [1,5,6,10]) && $user_list['item_name'] === '翻译组') {
                               return Html::a('<span class="glyphicon glyphicon-remove  btn btn-warning btn-sm">取消订单</span>', 'javascript:void(0);', ['title' => '取消订单', 'onclick' => 'cancleOrder('.$model->id.',"'.$model->status_array[$model->status].'")'] );
                            }
                        },
                        'edit_order' => function ($url, $model, $key) {
                            // 客服修改的权限
                            $user_id = Yii::$app->user->id;
                            $Model = new User();
                            $user_list = $Model->find()
                                        ->select('auth_assignment.item_name')
                                        ->leftJoin('auth_assignment','auth_assignment.user_id = user.id')
                                        ->where('user.id = '.$user_id)
                                        ->asArray()
                                        ->one();
                            if (!in_array($model->status, [4,5,6,8,9,13,14,15,16]) && $user_list['item_name'] === '翻译组') {
                               return Html::a('<span class="glyphicon glyphicon-pencil  btn btn-warning btn-sm">修改发货</span>', 'javascript:void(0);', ['title' => '修改发货', 'onclick' => 'editOrder('.$model->id.',"'.$model->status_array[$model->status].'")'] );
                            }
                        }
                    ],
                ]
            ],
        ]); ?>
    </div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    $(".check-stock").on("click", function ()
    {
        var keys = $("#select").yiiGridView("getSelectedRows");
        if (!keys.length)
        {
            alert('请选择订单');
        }
        else
        {
            var arr = String(keys).split(",");
            $.ajax({
                url:"/orders-audit/check-stock",
                type:'POST',
                dataType:'json',
                data:{
                    'id_arr':arr
                },
                success:function(data){
                    console.log(data);
                    alert(data.msg);
                    window.location.reload();
                }
            });
        }
    });

    var previous = '';
    <?php $this->beginBlock('select_status_js') ?>
    $("#select_status").on('focus', function () {
        previous = this.value;
    });
    <?php $this->endBlock() ?>
    <?php $this->registerJs($this->blocks['select_status_js']); ?>

    function changeStatus( id) {
        var status_value =  $("select[name='select_status_"+id+"']").val();
        $.post("/orders-audit/change-status", {orderId: id, status: status_value}, function (data) {
            data = JSON.parse(data);
            if(data.res === 0)
            {
                alert(data.msg);
                window.location.reload()
            }
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
<?php $this->registerJsFile(Yii::$app->request->baseUrl . "/js/layer/layer.js", ['depends' => ['app\assets\AppAsset']]); ?>
<script>
    // jieson 2018.11.08
    function cancleOrder(id, status)
    {
        // 取消订单
        layer.prompt({title: "当前订单："+id+"，状态为："+status+"，确认取消订单吗?", formType: 2}, function(text, index){
            if (text == '') {
                layer.msg('取消原因不能为空！');return false;
            }
            $.ajax({
                type: 'post',
                data: {id:id,notes:text},
                dataTyoe: 'json',
                url: '/replace/cancle-order',
                success: function(el) {
                    var rt = eval("("+el+")");
                    if (rt.status == 1) {
                        layer.msg(rt.msg, {icon:1},function(){location.reload();});
                    } else if (rt.status == 2) {
                        // 已发货，
                        layer.alert(rt.msg, {
                            skin: 'layui-layer-lan'
                            ,btn: ['是的', '取消']
                            ,anim: 0 //动画类型
                        },function(yes){
                            $.post("/replace/cancle-order-sended", {id:id,notes:text}, function(res){
                                res = eval("("+res+")");
                                if (res.status == 1) {
                                    layer.msg(res.msg, {icon:1}, function(){location.reload();});
                                } else if (res.status == -1) {
                                    layer.msg(res.msg, {icon:0});
                                } else {
                                    layer.msg(res.msg, {icon:0});
                                }
                            });
                        });
                    } else {
                        layer.msg('取消失败，请稍候重试！',{icon:0});
                    }
                }
            });
        });
    }

    function editOrder(id, status)
    {
        // 编辑订单
        layer.open({
            type: 2,
            title: '修改订单:'+id+'，状态：'+status,
            area: ['980px', '680px'], //宽高
            maxmin: true,
            // shadeClose: true,
            content: '/replace/replace?id='+id
        });
    }
</script>
