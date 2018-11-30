<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker; 

/* @var $this yii\web\View */
/* @var $model app\models\OrdersSearch */
/* @var $form yii\widgets\ActiveForm */

$websiteModel = new \app\models\Websites();
?>

<div class="orders-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row" style="display: none" id="search-box">
        <div class="col-md-6">
            <?= $form->field($model, 'id')->textArea(['rows' => '5']); ?>
            <div class="row form-group">
                <div class="col-md-12"><label class="control-label">下单时间</label></div>
                <div class="col-md-6">
                    <?php
                    echo DateTimePicker::widget([
                        'name' => 'order_time_begin',
                        'options' => ['placeholder' => '下单范围查询-开始', 'style' => 'width:200px;'],
                        //注意，该方法更新的时候你需要指定value值
                        'value' => $orderTimeBegin,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'startView'=>2,    //其实范围（0：日  1：天 2：年）
                            'minView'=>2,  //最大选择范围（年）
                            'maxView'=>2,  //最小选择范围（年）
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    echo DateTimePicker::widget([
                        'name' => 'order_time_end',
                        'options' => ['placeholder' => '下单范围查询-结束', 'style' => 'width:200px;'],
                        //注意，该方法更新的时候你需要指定value值
                        'value' => $orderTimeEnd,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'startView'=>2,    //其实范围（0：日  1：天 2：年）
                            'minView'=>2,  //最大选择范围（年）
                            'maxView'=>2,  //最小选择范围（年）
                        ]
                    ]);
                    ?>
                </div>
            </div>

            <?php  //echo $form->field($model, 'country')->dropDownList([ '' => "请选择" ] + $websiteModel->country) ?>
            <?php  echo $form->field($model, 'country')->checkBoxList($websiteModel->country)?>
            <?php  echo $form->field($model, 'is_pdf')->dropDownList([ '' => "请选择" ,'1'=>'是','0'=>'否']) ?>
            <?php  echo $form->field($model, 'is_print')->dropDownList([ '' => "请选择" ,'1'=>'是','2'=>'否']) ?>

            <?php echo $form->field($model,'status')->checkBoxList(\app\models\Orders::$status_arr)?>
            <?php // echo $form->field($model, 'status')->dropDownList([
            //                '' => '请选择',
            //                1 => '待确认',
            //                2 => '已经确认',
            //                3 => '已采购',
            //                4 => '已发货',
            //                5 => '签收',
            //                6 => '拒签',
            //                7 => '已入库',
            //                8 => '已打包',
            //                9 => '已回款',
            //                10 => '已取消',
            // ]);
            ?>
            <?php
            $userModel = new \app\models\User();
            ?>
            <?php  echo $form->field($model, 'uid')->dropDownList([''=>'销售']+$userModel->getUsers()) ?>
            <div class="form-group field-orderssearch-lc">
                <label class="control-label" for="orderssearch-spu">spu</label>
                <?=Html::input('text','spu',$spu,['class' => 'form-control','placeholder'=>'']);?>

                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'website_id') ?>

            <?= $form->field($model, 'product') ?>
            <?php  echo $form->field($model, 'name') ?>
            <?php  echo $form->field($model, 'mobile') ?>

            <?php  echo $form->field($model, 'lc') ?>

            <?php  echo $form->field($model, 'lc_number') ?>

            <?php  echo $form->field($model, 'email') ?>

        </div>
    </div>

    <div class="form-group">
        <button type="button" onclick="$('#search-box').toggle()" class="btn btn-default">高级搜索</button>
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?php if($is_select != 1) :?>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/orders/import">批量更新订单</a></div>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/orders/import-payment-collection-bill">回款单导入</a></div>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/orders/get-pdf">面单下载</a></div>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/orders/push-order">订单接口推送</a></div>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/orders/import-lc">运单号导入</a></div>
        <?php endif;?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
