<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\dialog\DialogAsset;
use kartik\export\ExportMenu;
use Yii;
use kartik\dialog\Dialog;

DialogAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Purchases */

$this->title = '采购单：'.$model->order_number;
$this->params['breadcrumbs'][] = ['label' => '采购单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['model'] = $model;
$status = $model->status_array[$model->status];
echo Dialog::widget();
$skuModel = new \app\models\ProductsVariant();
?>
<div class="purchases-view box box-primary">
    <div class="box-header">

        <button type="submit" class="btn btn-primary back" style="margin-bottom: 10px;margin-right: 10px;margin-top: 10px;width: 70px;">返回</button>

        <button class="btn btn-flat btn-error" type="button" onclick="refoundOrder(<?=$model->id?>)">退款</button>

        <?php if($model->status == 0) : ?>
            <?=Html::a('<span class="btn btn-info">确认采购</span>', '/purchases/confirm-purchases?id='.$model->id, ['title' => '确认采购'] ) ;?>
        <?php endif;?>

        <?= Html::a('修改采购单', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>

        <?php if($model->status == 5 || $model->status == 6) : ?>
        <?= Html::a('手动入库', 'javascript:void(0);', ['class' => 'btn btn-success btn-flat', 'onclick' => "setSuccess($model->id)"]) ?>
        <?php endif;?>

        <?php
        echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">采购单导出: </div>';

        echo ExportMenu::widget([
            'dataProvider' => $itemProvider,
            'columns' => [
                'id',
                [
                    'attribute' => '采购单号',
                    'value' => function($model){
                        return "\t".$this->params['model']->order_number."\t";
                    },

                ],

                [
                    'attribute' => '总价',
                    'value' => function($model){
                        return $this->params['model']->amaount;
                    },

                ],
                [
                    'attribute' => '采购状态',
                    'value' => function($model){
                        return $this->params['model']->status_array[$this->params['model']->status];
                    },
                    'filter' => [
                        '草稿',
                        '已确认',
                        '已采购',
                        '收货中'
                    ]
                ],
                [
                    'attribute' => '供应商',
                    'value' => function($model){
                        return $this->params['model']->supplier;
                    },

                ],
                [
                    'attribute' => '采购平台',
                    'value' => function($model){
                        return $this->params['model']->platform;
                    },

                ],
                [
                    'attribute' => '平台订单号',
                    'value' => function($model){
                        return $this->params['model']->platform_order;
                    },

                ],
                [
                    'attribute' => '物流单号',
                    'value' => function($model){
                        return $this->params['model']->platform_track;
                    },

                ],
                [
                    'attribute' => '添加时间',
                    'value' => function($model){
                        return $this->params['model']->create_time;
                    },

                ],
                [
                    'attribute' => '备注',
                    'value' => function($model){
                        return $this->params['model']->notes;
                    },

                ],
                [
                    'attribute' => 'sku',
                    'value' => function($model){
                        return $model->sku;
                    },

                ],
                [
                    'attribute' => '数量',
                    'value' => function($model){
                        return "\t".$model->qty."\t";
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
                    'attribute' => '收货日期',
                    'value' => function($model){
                        $sql = "select rl.create_date from receipt_logs_items rli left join receipt_logs rl on rl.id=rli.receipt_id where rli.order_number='{$model->purchase_number}' and rli.sku ='{$model->sku}'";
                        $receiptDate = Yii::$app->db->createCommand($sql)->queryOne();
                        return $receiptDate['create_date']?$receiptDate['create_date']:'未收货';
                    },

                ]
            ]
        ]);
        ?>
        <?php 
        echo ($model->status == 5 && !empty($feedback) )? Html::Button('回复反馈', ['class' => 'btn btn-success btn-flat', 'onclick' => "respond()"]): '';
        ?>
    </div>
    <div class="box-body table-responsive">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'order_number',
                'create_time',
                'amaount',
                'supplier',
                'status'=>[
                    'attribute' => 'status',
                    'value' => $status
                ],
                [
                    'label' => '操作人',
                    'format' => 'raw',
                    'value' => function($data){
                        $items = $data->getUsers($data->uid);
                        $html = $items->username;
                        //$images = json_decode($data->images);
                        //$html = '';
                        //foreach($images as $v){
                        //  $html .= '<img src="'.$v.'" width="100">';
                        //}

                        return $html;
                    }
                ],

                [
                    'attribute' => '预计到货时间',
                    'value' => function($model){
                        return $this->params['model']->delivery_time;
                    },

                ],
                [
                    'attribute' => '运费',
                    'value' => function($model){
                        return $this->params['model']->shipping_amount;
                    },

                ],

                'platform',
                'platform_order',
                'track_name',
                'platform_track',
                'notes',
                [
                    'attribute' => '收货反馈',
                    'value' => $feedback,
                    'format'=>'html',
                ]
                
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive" >
        <table class="table table-bordered" >
            <th></th>
            <th>sku</th>
            <th>颜色</th>
            <th>尺寸</th>
            <th>采购数量</th>
            <th>单价</th>
            <th>购买链接</th>
            <th>实收数量</th>
            <th>退货数量</th>
            <th>3天销量</th>
            <th>7天销量</th>
            <th>收货时间</th>
            <?php
            if($items_list) :$i=0;foreach($items_list as $key=>$list):
                $sku_info = $skuModel->find()->where(['sku' => $list['sku']])->one();
                ?>
                <tr <?php if($model->status == 5 && ($list['delivery_qty'] !== $list['qty'])){ echo 'style="background-color:#ECF0F5"';} ?>>
                    <td><img width="100" src="<?=$sku_info->image?>" </td>
                    <td>
                        <?php echo $list['sku'];?>
                    </td>
                    <td><?=$sku_info->color?> </td>
                    <td><?=$sku_info->size?> </td>
                    <td>
                        <?php echo $list['qty'];?>
                    </td>
                    <td>
                        <?php echo $list['price'];?>
                    </td>
                    <td>
                        <?php
                        if($list['buy_link'])
                        {
                            echo '<a href="'.$list['buy_link'].'">点击购买</a>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $list['delivery_qty'];?>                    </td>
                    <td>
                        <?php echo $list['refound_qty'];?>
                    </td>
                    <td>
                        <?php echo $list['qty3_num'];?>
                    </td>
                    <td>
                        <?php echo $list['qty7_num'];?>
                    </td>
                    <td>
                        <?php 
                            $sql = "select rl.create_date from receipt_logs_items rli left join receipt_logs rl on rl.id=rli.receipt_id where rli.order_number='{$model->order_number}' and rli.sku ='{$list['sku']}'";
                            $receiptDate = Yii::$app->db->createCommand($sql)->queryOne();
                            echo $receiptDate['create_date']?$receiptDate['create_date']:'未收货';
                        ?>
                    </td>
                </tr>
                <?php $i++;endforeach;endif;?>
        </table>

    </div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    //页面跳转到审核页
    $(".back").on('click',function () {
        window.location.assign('/purchases/index');
    });

    function refoundOrder(order_id) {
        krajeeDialog.confirm("是否确认订单退款?", function (result) {
            if (result) {
                $.get("/purchases/refound-order", {id:order_id}, function (data) {
                    if(data)
                    {
                        krajeeDialog.alert("操作成功");
                        <?php sleep(2); ?>
                        location.reload();
                    }
                })
            }
        });
    }
    // 回复反馈
    function respond()
    {
        krajeeDialog.prompt({label:'回复收货反馈', placeholder:'回复内容....'}, function (result) {
            if (result) {
                var order_number = "<?=  $model->order_number ?>";
                $.post("/purchases/handlemsg",{order_number:order_number,contents:result},function(res){
                    if (res) {
                        krajeeDialog.alert('提交成功: \n\n' + res);
                        <?php sleep(2) ?>
                        location.reload();
                    } else {
                        krajeeDialog.alert('提交失败，刷新重试！');
                    }
                });
                
            }
            
        });
    }

    function setSuccess($id)
    {
        krajeeDialog.confirm("确定设置为已入库吗?", function (result) {
            if (result) {
                krajeeDialog.prompt({label:'备注', placeholder:'备注原因....'}, function (result) {
                    $.post("/purchases/set-inware", {id: $id, contents: result}, function(res){
                        if (res == 1) {
                            krajeeDialog.alert('操作成功！');
                            <?php sleep(2) ?>
                            location.reload();
                        } else {
                            krajeeDialog.alert('提交失败，刷新重试！');
                        }
                    });

                });
                
            }
        });
    }
</script>
