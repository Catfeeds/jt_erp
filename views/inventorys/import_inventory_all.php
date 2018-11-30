<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '全盘单批量导入';
?>
<div class="orders-index box box-primary">
    <div class="box-body table-responsive">
        <div class="form-actions">
            <button type="submit" class="btn btn-primary back" style="margin-bottom: 10px;margin-right: 10px;margin-top: 10px;">返回</button>
        </div>
        <div style="margin: 4px;">
            <lable style="font-size: 16px;">导入的excel格式：</lable><br>
            <lable style="font-size: 16px;">库位编号, SKU, 数量</lable><br>
            <font color="#ff0000"><?= $notice ?></font>
        </div>
        <?php
        echo Html::beginForm('import-inventory-all','post', ["enctype" => "multipart/form-data"]);

        echo Html::fileInput('inventorysData');
        echo "<br>";
        echo Html::submitButton('上传',['class'=>'btn btn-primary']);
        echo Html::endForm();
        ?>
        <a href="/uploadTemplate/<?=urlencode('盘存单.xlsx')?>" target="_blank">下载模板</a>
    </div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    //页面跳转到审核页
    $(".back").on('click',function () {
        window.location.assign('/inventorys/index');
    });
</script>
