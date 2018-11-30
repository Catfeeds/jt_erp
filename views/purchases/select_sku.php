<?php

use yii\helpers\Html;

$this->title = 'sku在途数量查询';
?>
<div class="orders-index box box-primary">
    <button type="submit" class="btn btn-primary back" style="margin-bottom: 10px;margin-right: 10px;margin-top: 10px;">返回</button>
    <div class="box-body table-responsive">
        <div>
            <lable style="font-size: 16px;">请从excel中导入sku</lable><br>
        </div>
        <?php
        echo Html::beginForm('select-sku','post', ["enctype" => "multipart/form-data"]);
        echo "<br>";
        echo '<textarea name="data" style="width:420px;height:300px;">'.$data.'</textarea>';
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo Html::submitButton('查询',['class'=>'btn btn-primary save']);
        echo Html::endForm();
        ?>
    </div>
    <?php foreach ($info['error'] as $row){?>
        <div style="color:red;"><?= $row;?></div>
    <?php }?>
    <?php foreach ($info['info'] as $row){?>
        <div style="color:darkgreen;"><?= $row;?></div>
    <?php }?>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    $(".back").on('click',function () {
        window.location.assign('/purchases/index');
    });
</script>