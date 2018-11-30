<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\grid\EditableColumn;
use kartik\datetime\DateTimePicker;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderStatusChangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $orderTimeBegin yii\data\ActiveDataProvider */
/* @var $orderTimeBegin yii\data\ActiveDataProvider */
/* @var $country yii\data\ActiveDataProvider */

$this->title = '订单推送监控';
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
$button_data = ['class' => 'yii\grid\ActionColumn','header'=>'','template' => "<button class='btn  push_order_again'>重新推送</button><span class='push_notice' style='display: none;color: red'>推送中,请稍后...</span>" ];
?>
<div class="orders-index box box-primary">
    <div class="box-header with-border">

    </div>
    <div class="box-body table-responsive">
        <?= $this->render('_search', ['model' => $searchModel, 'orderTimeBegin' => $orderTimeBegin, 'orderTimeEnd' => $orderTimeBegin, 'country' => $country]); ?>
        <button class='btn delete_push_order' style="margin-left: 12px;width: 92px;">清除</button>
        <?= GridView::widget([

            'dataProvider' => $dataProvider,

            'columns' => [
                'id',
                'id_order',
                [
                    'label' => '国家',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $info = \app\models\Orders::find()->select('country')->where(array('id'=>$data->id_order))->one();
                        return \app\models\Websites::$country_array[$info['country']];
                    }
                ],
                'count',
                'return_content',
                'create_time',
                'last_get_time',
                $button_data,
            ],
        ]); ?>
    </div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    $(".push_order_again").on('click',function () {
        var id_order = $(this).parents("tr").children().eq(1).html();
        $(this).parents("tr").children().eq(7).find(".push_notice").css('display','block');
        $.ajax({
            url:"/get-shipping-no/push-order-again",
            type:'POST',
            dataType:'json',
            data:{
                'id_order':id_order
            },
            success:function(data){
                $(this).parents("tr").children().eq(7).find(".push_notice").css('display','none');
                console.log(data);
                window.location.reload();
            }
        });
    })

    $(".delete_push_order").on('click',function () {
        $.ajax({
            url:"/get-shipping-no/delete-order",
            type:'POST',
            dataType:'json',
            data:{},
            success:function(data){
                console.log(data);
                window.location.reload();
            }
        });
    });
</script>

