<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $info yii\widgets\ActiveForm */
/* @var $total yii\widgets\ActiveForm */
/* @var $success yii\widgets\ActiveForm */
/* @var $files yii\widgets\ActiveForm */
/* @var $data yii\widgets\ActiveForm */

$this->title = '获取面单';
?>
<div class="orders-index box box-primary">
    <div class="box-body table-responsive">
        <div>
            请从excel中导入订单号<br>
        </div>
        <?php
        echo Html::beginForm('get-pdf','post', ["enctype" => "multipart/form-data"]);
        echo "<br>";
        echo '<textarea name="data" style="width:420px;height:300px;">'.$data.'</textarea>';
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo Html::submitButton('面单导出',['class'=>'btn btn-primary save']);
        echo Html::endForm();
        ?>
    </div>
    <?php if($total){;?>
        共导入:<?= $total;?>个订单   失败:<?= count($info['error']);?> ;面单验证成功:<?= $success?>;
    <?php };?>
    <?php foreach ($info['error'] as $row){?>
        <div style="color:red;"><?= $row;?></div>
    <?php }?>
</div>