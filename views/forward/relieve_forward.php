<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $info yii\widgets\ActiveForm */
/* @var $total yii\widgets\ActiveForm */
/* @var $success yii\widgets\ActiveForm */
/* @var $files yii\widgets\ActiveForm */
/* @var $data yii\widgets\ActiveForm */

$this->title = '解除待转运订单';
$warehouse = new \app\models\Warehouse();
$code = $warehouse->stockForward();
?>
<div class="orders-index box box-primary">
    <div class="box-body table-responsive">
        <div class="form-actions">
            <button type="submit" class="btn btn-primary back" style="margin-bottom: 10px;margin-right: 10px;margin-top: 10px;width: 70px;">返回</button>
        </div>
        <div>
            <lable style="font-size: 16px;">请从excel中导入订单号(待转运)</lable>&nbsp;&nbsp;<br>
        </div>
        <?php
        echo Html::beginForm('relieve-forward','post', ["enctype" => "multipart/form-data"]);
        echo "<br>";
        echo '<textarea name="data" style="width:420px;height:300px;">'.$data.'</textarea>';
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo Html::submitButton('导入',['class'=>'btn btn-primary save']);
        echo Html::endForm();
        ?>
    </div>
    <?php if($total){;?>
        共导入:<?= $total;?>个订单   失败:<?= count($info['error']);?> ;待转运订单解除成功:<?= $success?>;
    <?php };?>
    <?php foreach ($info['error'] as $row){?>
        <div style="color:red;"><?= $row;?></div>
    <?php }?>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    //页面跳转到审核页
    $(".back").on('click',function () {
        window.location.assign('/forward/index');
    });
</script>
