<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '订单物流状态导入';
?>
<div class="orders-index box box-primary">
    <div class="box-body table-responsive">
        <div class="form-actions">
            <button type="submit" class="btn btn-primary back" style="margin-bottom: 10px;margin-right: 10px;margin-top: 10px;">返回</button>
        </div>
        <div style="margin: 4px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<lable style="font-size: 20px;">导入的excel格式：</lable><br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<lable style="font-size: 20px;">订单号, 运单号, 物流状态</lable><br>
            <font color="#ff0000"></font>
        </div>
        <?php
        echo Html::beginForm('import-all','post', ["enctype" => "multipart/form-data"]);
        echo Html::fileInput('shippingLogisticsStateData');
        echo "<br>";
        echo Html::submitButton('上传',['class'=>'btn btn-primary']);
        echo Html::endForm();
        ?>
        <a href="/uploadTemplate/<?=urlencode('物流状态.xlsx')?>" target="_blank">下载模板</a>
    </div>
    <?php foreach ($notice as $row){?>
        <div style="color:red;"><?= $row;?></div>
    <?php }?>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    //页面跳转到审核页
    $(".back").on('click',function ()
    {
        window.location.assign('/shipping-logistics-state/index');
    });
</script>
