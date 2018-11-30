<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\OrdersSearch */
/* @var $orderTimeBegin app\models\OrdersSearch */
/* @var $orderTimeEnd app\models\OrdersSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $time yii\widgets\ActiveForm */
/* @var $country yii\widgets\ActiveForm */

?>

<div class="orders-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row" style="display: none" id="search-box">
        <div class="col-md-6">
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
            <div class="form-group field-orderssearch-lc">
                <label class="control-label" for="orderssearch-spu">订单状态</label><br>
                <select name="status" style="height: 32px;width: 360px;">
                    <option value="0">请选择</option>
                    <?php foreach (\app\models\Orders::$status_arr as $key => $status){?>
                        <option value="<?= $key;?>"><?= $status;?></option>
                    <?php }?>
                </select>

                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group field-orderssearch-lc">
                <label class="control-label" for="orderssearch-spu">国家</label><br>
                <select name="country" style="height: 32px;width: 360px;">
                    <option value="0">请选择</option>
                    <?php foreach (\app\models\Websites::$country_array as $key => $value){?>
                        <?php if($key == $country){?>
                            <option value="<?= $key;?>" selected="selected"><?= $value;?></option>
                        <?php }else{?>
                            <option value="<?= $key;?>"><?= $value;?></option>
                       <?php }?>
                    <?php }?>
                </select>

                <div class="help-block"></div>
            </div>
            <div class="form-group field-orderssearch-lc">
                <label class="control-label" for="orderssearch-spu">节点耗时</label>
                <?=Html::input('text','time',$time,['class' => 'form-control','placeholder'=>'请输入数字']);?>
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <button type="button" onclick="$('#search-box').toggle()" class="btn btn-default">高级搜索</button>
    </div>
    <?php ActiveForm::end(); ?>

