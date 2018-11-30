<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\LocationStock;
use app\models\Purchases;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\Purchases */
/* @var $form yii\widgets\ActiveForm */
$skuModel = new \app\models\ProductsVariant();

Modal::begin([
    'id' => 'create-modal',
    'header' => '<h4 class="modal-title">收货反馈：</h4>',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
]); 
echo '<input type="text" placehloder="反馈内容"/>';
$requestUrl = Url::toRoute('receipt-feedback?order_number='.$model->order_number.'&track='.$track);
$js = <<<JS
    $.get('{$requestUrl}', {}, function (data) {
            $('.modal-body').html(data);
        } 
    );
JS;
$this->registerJs($js);
Modal::end();
?>
<style>.btn_c{padding: 4px 8px;}</style>
<div class="purchases-form box box-primary">
    <div class="box-body table-responsive">
        <strong>
            采购单：<?=$model->order_number?>
            <span>
            <?php
            // Html::a('收货反馈', '#', [
            //     'class' => 'btn btn-primary btn_c',
            //     'data-toggle' => 'modal', 
            //     'data-target' => '#create-modal'    //此处对应Modal组件中设置的id
            //     ]) 
            ?>
        </strong>
        <table class="table table-bordered">
            <tr>
                <th>平台订单号</th>
                <td><?= $model->platform_order ?></td>
                <th>采购平台</th>
                <td><?= $model->platform ?></td>
                <th>供应商</th>
                <td><?= $model->supplier; ?></td>
            </tr>
            <tr>
                <th>快递单号</th>
                <td><?= $model->platform_track ?></td>
                <th>总价</th>
                <td><?=$model->amaount?></td>
                <th>采购状态</th>
                <td><?php
                    $purchasesModel = new Purchases();
                    echo $purchasesModel->status_array[$model->status];
                    ?></td>
            </tr>
            <tr>
                <th>备注</th>
                <td><?= $model->notes; ?></td>
            </tr>
        </table>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-bordered">
            <tr>
                <td></td>

                <th>颜色</th>
                <th>尺寸</th>
                <th></th>
                <th width="100">SKU</th>
                <th width="50">已收</th>
                <th width="70">采购量</th>
                <th width="80">本次应收</th>
                <th width="80">本次实收</th>
                <th width="50">库位</th>
                <th>退货</th>
                <th width="100">说明</th>
            </tr>
            <?php if ($items_list) :
                foreach ($items_list as $key => $list):
                    $sku_info = $skuModel->find()->where(['sku' => $list['sku']])->one();
                    $stock_code = 'SZ001';
                    $location_stock = LocationStock::find()->where(['sku' => $list['sku']])->andWhere(['stock_code' => $stock_code])->one();
                    $location_code = 'A001';
                    $area_code = 'A';
                    if($location_stock)
                    {
                        $location_code = $location_stock->location_code;
                        $area_code = $location_stock->area_code;
                    }
                    $location_codes = \app\models\StockLocationCode::find()->where([
                        'stock_code' => $stock_code
                    ])->andWhere([
                        'area_code' => $area_code
                    ])->all();
                    $stock_location_codes = [];
                    foreach($location_codes as $vcode)
                    {
                        $stock_location_codes[strtoupper($vcode->code)] = strtoupper($vcode->code);
                    }

                    ?>
                    <tr onmouseover="this.attr.className='crf';" onmouseout="this.attr.className=''" <?php if($model->status == 5 && ($list['delivery_qty'] !== $list['qty'])){ echo 'style="background-color:#ECF0F5"';} ?> >
                        <td><img width="100" src="<?= $sku_info->image ?>"></td>

                        <td><?= $sku_info->color ?> </td>
                        <td><?= $sku_info->size ?> </td>
                        <td style="width:30px;"><button type="button" onclick="window.open('/receipt/print-sku-code-single?sku=<?= $list['sku'] ?>');" class="btn btn-primary btn_c">打印</button></td>
                        <td>
                            <strong><?php echo $list['sku']; ?></strong>
                            <input type="hidden" name="receipt[<?php echo $list['id']; ?>][sku]" value="<?php echo $list['sku']; ?>">
                            <input type="hidden" name="receipt[<?php echo $list['id']; ?>][id]" value="<?php echo $list['id']; ?>">
                            <input type="hidden" name="receipt[<?php echo $list['id']; ?>][order_number]" value="<?=$model->order_number?>">
                        </td>
                        <td><?php echo $list['delivery_qty']; ?></td>
                        <td><?php echo $list['qty']; ?></td>
                        <td>
                            <?=$list['qty']-$list['delivery_qty']-$list['refound_qty']?>
                            <input type="hidden" name="sku_lab[]" value="<?=$list['sku']?>:<?=$list['qty']-$list['delivery_qty']-$list['refound_qty']?>">
                        </td>
                        <td>
                            <input type="hidden" name="receipt[<?php echo $list['id']; ?>][buy_qty]" value="<?=$list['qty']-$list['delivery_qty']-$list['refound_qty']?>">
                            <input type="text" class="input-small"
                                   data-id="<?= $list['id'] ?>" data-get="<?=$list['qty']-$list['delivery_qty']-$list['refound_qty']?>" name="receipt[<?php echo $list['id']; ?>][get_qty]"
                                   value="" placeholder="本次应收：<?=$list['qty']-$list['delivery_qty']-$list['refound_qty']?>" onchange="checkQty(this)">
                        </td>
                        <td><?= Html::dropDownList('receipt[' . $list['id'] . '][location_code]', strtoupper($location_code), $stock_location_codes) ?></td>
                        <td>
                            <input type="text" class="input-small"
                                   data-id="<?= $list['id'] ?>" name="receipt[<?php echo $list['id']; ?>][refound_qty]"
                                   value="<?php echo $list['refound_qty']; ?>" placeholder="退货数量">
                        </td>
                        <td><?php echo $list['info']; ?></td>
                    </tr>
                    <?php $i++;endforeach;endif; ?>
        </table>
    </div>
</div>