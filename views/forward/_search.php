<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\OrdersSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $add_time_begin yii\widgets\ActiveForm */
/* @var $add_time_end yii\widgets\ActiveForm */
/* @var $forward_time_begin yii\widgets\ActiveForm */
/* @var $forward_time_end yii\widgets\ActiveForm */

$websiteModel = new \app\models\Websites();
?>

<div class="orders-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row" style="display: none" id="search-box">
        <div class="col-md-6">
            <?= $form->field($model, 'id_order')->textArea(['rows' => '5']); ?>

            <?php  echo $form->field($model, 'country')->dropDownList([ '' => "请选择" ] + $websiteModel->country) ?>

            <?php echo $form->field($model,'status')->dropDownList([ '' => "请选择" ] + \app\models\Forward::$status_arr)?>

            <?= $form->field($model, 'lc_number') ?>
        </div>
        <div class="col-md-6">
            <div class="row form-group">
                <div class="col-md-12"><label class="control-label">导入转寄仓时间</label></div>
                <div class="col-md-6">
                    <?php
                    echo DateTimePicker::widget([
                        'name' => 'add_time_begin',
                        'options' => ['placeholder' => '范围查询-开始', 'style' => 'width:200px;'],
                        //注意，该方法更新的时候你需要指定value值
                        'value' => $add_time_begin,
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
                        'name' => 'add_time_end',
                        'options' => ['placeholder' => '范围查询-结束', 'style' => 'width:200px;'],
                        //注意，该方法更新的时候你需要指定value值
                        'value' => $add_time_end,
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
        </div>
        <div class="col-md-6">
            <div class="row form-group">
                <div class="col-md-12"><label class="control-label">匹配转寄仓时间</label></div>
                <div class="col-md-6">
                    <?php
                    echo DateTimePicker::widget([
                        'name' => 'forward_time_begin',
                        'options' => ['placeholder' => '范围查询-开始', 'style' => 'width:200px;'],
                        //注意，该方法更新的时候你需要指定value值
                        'value' => $forward_time_begin,
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
                        'name' => 'forward_time_end',
                        'options' => ['placeholder' => '范围查询-结束', 'style' => 'width:200px;'],
                        //注意，该方法更新的时候你需要指定value值
                        'value' => $forward_time_end,
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

            <?php  echo $form->field($model, 'new_id_order') ?>

            <?php  echo $form->field($model, 'new_lc_number') ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <button type="button" onclick="$('#search-box').toggle()" class="btn btn-default">高级搜索</button>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/forward/import-forward">订单批量导入转寄仓</a></div>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/forward/relieve-forward">解除待转运订单</a></div>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/forward/get-forward">获取匹配转寄仓数据</a></div>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/forward/get-forward-abnormal">获取非正常匹配转寄仓数据</a></div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
