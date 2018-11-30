<?php

/* @var $this yii\web\View */

$this->title = '君天诺信管理平台';
?>
<div class="site-index">

    <div class="jumbotron">
        <h2>欢迎来到君天诺信管理平台!</h2>

        <p class="lead">Welcome to kingdom sky management platform.</p>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2><?=$sku?></h2>

                <p>
                    <?php
                    if($sku):
                    //可用库存
                    $number = $stockModel->inventoryBySku($sku);
                    //在途库存
                    $ztnumber = $stockModel->transitInventoryBySku($sku);
                    //已采购与待采购订单SKU量
                    $ycgnumber = $stockModel->countPurchaseTotalBySku($sku);
                    //中间表未采购
                    $rep_trans = $stockModel->repTransBySku($sku);
                    //补采数量
                    $rep = $number + $ztnumber + $rep_trans -  $ycgnumber - 1;
                    ?>
                    <ul>
                    <li>可用库存: <?=$number?></li>
                    <li>在途库存: <?=$ztnumber?></li>
                    <li>已采购与待采购订单SKU量: <?=$ycgnumber?></li>
                    <li>中间表未采购: <?=$rep_trans?></li>
                    <li>补采数量: <?=$rep?></li>
                </ul>
                <?php endif;?>
                </p>

            </div>
            <div class="col-lg-4" style="display:none">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4" style="display:none">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
