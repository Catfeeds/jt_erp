<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '待操作订单';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="orders-index box box-primary">
    <div class="box-header with-border">

    </div>
    <div class="box-body table-responsive">
        <?php echo $this->render('_search', ['model' => $searchModel, 'orderTimeBegin' => $orderTimeBegin, 'orderTimeEnd' => $orderTimeEnd]); ?>
        <button type="submit" class="btn btn-primary check-stock" style="margin: 10px;">批量更新采购</button>
        <?= GridView::widget([

            'dataProvider' => $dataProvider,

            'layout' => "{items}\n{summary}\n{pager}",

            'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'select'],
            'columns' => [
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'name' => 'id'
                ],
                'id',
                [
                    'attribute' => 'country',
                    'label' => '国家',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->country;
                    }
                ],
                [
                    'attribute' => 'create_date',
                    'label' => '下单时间',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->create_date;
                    }
                ],
                [
                    'label' => '订单详情',
                    'format' => 'raw',
                    'options' => ['style' => 'width:420px'],
                    'value' => function ($data) {
                        $items = $data->getItems($data->id);
                        $html = '';
                        foreach ($items as $sku) {
                            $product = $sku->getProduct();
                            if ($product)
                            {
                                $html .= '<span><img class="zoom" src="http://admin.kingdomskymall.net' . $sku->image . '" width="50">' . $sku->sku . ' X '.$sku->qty.' 颜色: ' . $product->color . ' 尺寸: ' . $product->size . '</span><br>';
                            }
                        }
                        return '<ol>' . $html . '</ol>';
                    }
                ],
                [
                    'label' => '匹配项',
                    'options' => ['style' => 'width:420px'],
                    'format' => 'raw',
                    'value' => function ($data) {
                        $n = 3;
                        $order_model = new \app\models\Orders();
                        //获取匹配项
                        $match_order_id_arr = \app\models\Forward::order_forward_warehouse_abnormal($data->id);
                        $i = 0;
                        $select = '';
                        if ($match_order_id_arr)
                        {
                            $select = '<select name="select_'.$data->id.'" style="width:400px;height:32px;">';
                            foreach ($match_order_id_arr as $id_order)
                            {
                                if ($i>=$n)
                                {
                                    break;
                                }
                                $info = $order_model->getItems($id_order);
                                $info_str = '';
                                foreach ($info as $sku)
                                {
                                    $product = $sku->getProduct();
                                    if ($product)
                                    {
                                        $color = $product->color ? ' 颜色: '.$product->color:' ';
                                        $size = $product->size ? ' 尺寸: '.$product->size:' ';
                                        $info_str .= '<span>'.$sku->sku.' X '.$sku->qty.$color.$size.'&nbsp;&nbsp;</span>';
                                    }
                                }
                                $select .= '<option value="a-'.$id_order.'">'.$info_str.'-> 转寄仓订单：'.$id_order.'</option>';
                                $i++;
                            }
                        }

                        //非正常库存匹配
                        if ($i <$n)
                        {
                            $stock_check = \app\models\Stocks::sku_stock_match($data->id);
                            if ($stock_check)
                            {
                                $info_str = '';
                                if ($i == 0)
                                {
                                    $select = '<select name="select_'.$data->id.'" style="width:400px;height:32px;">';
                                }

                                $sku_str = '';
                                foreach ($stock_check as $s => $value)
                                {
                                    foreach ($value as $sku => $qty)
                                    {
                                        $sku_str .='-'.$sku.'&'.$qty;
                                        if (in_array($sku,\app\models\Forward::$gift_arr))
                                        {
                                            $info_str .= '<span>赠品:'.$sku.' X '.$qty.'&nbsp;&nbsp;</span>';
                                        }
                                        $product = \app\models\OrdersItem::getProductBySku($sku);
                                        if ($product)
                                        {
                                            $color = $product->color ? ' 颜色: '.$product->color:' ';
                                            $size = $product->size ? ' 尺寸: '.$product->size:' ';
                                            $info_str .= '<span>'.$sku.' X '.$qty.$color.$size.'&nbsp;&nbsp;</span>';
                                        }
                                        break;
                                    }
                                }
                                $select .= '<option value="b'.$sku_str.'">'.$info_str.'->仓库</option>';
                            }
                        }
                        if ($i == 0)
                        {
                            $select .= "无匹配项";
                        }
                        else
                        {
                            $select .= '</select>';
                        }

                        return $select;
                    }
                ],
                ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => '{confirm} {cancel}',
                    'headerOptions' => ['width' => '240','align' => 'center'],
                    'buttons' => [
                        'confirm' => function ($url, $data, $key)
                        {
                            if (\app\models\Stocks::sku_stock_match($data->id) || \app\models\Forward::order_forward_warehouse_abnormal($data->id))
                            {
                                return Html::a('<span class="btn btn-info">确认</span>', 'javascript:void(0);',['onclick' => 'ordersConfirm('.$data->id.')']);
                            }
                            else
                            {
                                return '';
                            }
                        },
                        'cancel' => function ($url,$data,$key)
                        {
                            return Html::a('<span class="btn btn-info">采购</span>', 'javascript:void(0);',['onclick' => 'ordersCancel('.$data->id.')']) ;
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . "/js/layer/layer.js", ['depends' => ['app\assets\AppAsset']]); ?>
<script>
    $(".check-stock").on("click", function ()
    {
        var keys = $("#select").yiiGridView("getSelectedRows");
        if (!keys.length)
        {
            layer.msg('请先选择订单');
        }
        else
        {
            var arr = String(keys).split(",");
            $.ajax({
                url:"/orders-operated/cancel",
                type:'POST',
                dataType:'json',
                data:{
                    'id_arr':arr
                },
                success:function(data){
                    console.log(data);
                    layer.msg(data.msg);
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

    //订单更新为待采购
    function ordersCancel(id) {
        var arr = String(id).split(",");
        layer.confirm('您确定要更新为采购吗?',{btn: ['确定', '取消'],title:"提示"}, function(){
            $.ajax({
                type: "post",
                url:"/orders-operated/cancel",
                data:{
                'id_arr':arr
                },
                dataType: "json",
                async:false,
                success:function(data) {
                    console.log(data);
                    layer.msg(data.msg);
                    window.location.reload();
                }
            });
        });
    }

    //订单数据匹配库存
    function ordersConfirm(id) {
        var info = $("select[name='select_" + id + "']").val();
        layer.confirm('您确定要执行匹配操作吗?', {btn: ['确定', '取消'], title: "提示"}, function () {
            $.ajax({
                type: "post",
                url: "/orders-operated/order-confirm",
                data: {
                    orderId: id,
                    info: info
                },
                dataType: "json",
                async: false,
                success: function (data) {
                    console.log(data);
                    layer.msg(data.msg);
                    window.location.reload();
                }
            });
        });
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
