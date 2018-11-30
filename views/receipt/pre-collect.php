<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\dialog\Dialog;
$this->title = '收货反馈';
$this->params['breadcrumbs'][] = $this->title;
echo Dialog::widget();
?>
<style>.fl-right{float:right}.marginBttom{margin-bottom:6px}</style>
<div class="preCollect-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <?= $form->field($model, 'track_number')->textInput(['maxlength' => true, 'value'=>$data[0]['track_number']]) ?>
        <div class="form-group field-orderssearch-lc">
            <label class="control-label" for="orderssearch-spu">异常采购单</label>
            <?php foreach($data as $k => $v) { ?>
                <div class="form-control marginBttom">
                    <span><?=$v['order_number']?>——SKU：<?= $v['sku'] ?>：应收<?=$v['qty'] ?>  &nbsp;&nbsp; 实收<?= $v['delivery_qty']?> &nbsp;&nbsp;<?= $v['msg']?></span>
                    <input type="hidden" name="order_number[]" value="<?= $v['order_number'] ?>" />
                    <input type="hidden" name="sku[]" value="<?= $v['sku'] ?>" />
                    <?=Html::radioList('status'.$k, $v['status'],['2'=>'异常'],['class' => 'fl-right', 'onclick' => 'sureTrue(this)']);?>
                </div>
            <?php } ;?>
        </div>
        <?= $form->field($model, 'contents')->widget('kucha\ueditor\UEditor',[]);?>
        <?php //$form->field($model, 'status')->dropdownList([1 => "正常", 2 => "异常"])  ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('提交', ['class' => 'btn btn-success btn-flat']) ?>
        <input type="hidden" name="track_number" value="<?= $data[0]['track_number'] ?>" />
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function sureTrue()
    {
        var v= $('input[type=radio]:checked').val();
        if (v == 1) {
            krajeeDialog.confirm("确定采购单正常吗?", function (result) {
                if (!result) {
                    $("input[type='radio'][value='2']").prop("checked",true);
                }
            });
        }
    }

</script>