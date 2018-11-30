<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '物流结算导入';
?>
<div class="orders-index box box-primary">
    <div class="box-body table-responsive">
        <div class="form-actions">
            <button type="submit" class="btn btn-primary back" style="margin-bottom: 10px;margin-right: 10px;margin-top: 10px;">返回</button>
        </div>
        <div style="margin: 4px;">
            <lable style="font-size: 16px;">导入的excel格式：</lable><br>
            <lable style="font-size: 16px;">订单号, 运单号, 物流商, 回款金额，COD手续费，实际物流费，其他费用，货币，对账日期 </lable><br>
            <lable style="font-size: 16px;color: red">注意：1、费用请用负数表示  2、物流商,货币和对账日期需统一  3、金额和费用部分没有请用0表示</lable>
            <br>
        </div>
        <?php
        echo Html::beginForm('import-settlement','post', ["enctype" => "multipart/form-data"]);
        echo Html::fileInput('importSettlementData');
        echo "<br>";
        echo Html::submitButton('上传',['class'=>'btn btn-primary']);
        echo Html::endForm();
        ?>
        <a href="/uploadTemplate/<?=urlencode('物流结算.xlsx')?>" target="_blank">下载模板</a>
    </div>
    <?php foreach ($notice as $row){?>
        <div style="color:red;"><?= $row;?></div>
    <?php }?>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    //页面跳转到审核页
    $(".back").on('click',function () {
        window.location.assign('/shipping-settlement/index');
    });
</script>
