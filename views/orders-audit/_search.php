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

            <?php  echo $form->field($model, 'country')->dropDownList([ '' => "请选择" ] + $websiteModel->country) ?>

            <?php echo $form->field($model,'status')->checkBoxList(\app\models\Orders::$status_arr)?>

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
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <button type="button" onclick="$('#search-box').toggle()" class="btn btn-default">高级搜索</button>
    </div>

    <?php ActiveForm::end(); ?>


</div>
