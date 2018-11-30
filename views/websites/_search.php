<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\WebsitesBaseSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $orderTimeEnd yii\widgets\ActiveForm */
/* @var $orderTimeBegin yii\widgets\ActiveForm */
/* @var $domain_host yii\widgets\ActiveForm */
/* @var $user_arr yii\widgets\ActiveForm */
/* @var $uid yii\widgets\ActiveForm */
$websiteModel = new \app\models\Websites();
?>

<div class="websites-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row" style="display: none" id="search-box">
        <div class="col-md-6">
            <?= $form->field($model, 'id') ?>

            <?= $form->field($model, 'spu') ?>

            <?= $form->field($model, 'title') ?>

            <div class="form-group field-orderssearch-lc">
                <label class="control-label">站点地址</label>
                <?=Html::input('text','domain_host',$domain_host,['class' => 'form-control','placeholder'=>'']);?>
                <div class="help-block"></div>
            </div>

            <?= $form->field($model, 'sale_city')->dropDownList(['' => '选择']+$model->country) ?>

            <div class="form-group field-orderssearch-lc">
                <label class="control-label">开发人员</label><br>
                <select name="uid" style="width: 320px;height: 36px;">
                    <option value="0">请选择</option>
                <?php foreach ($user_arr as $id =>$username):?>
                    <?php if($uid == $id){;?>
                        <option value="<?= $id;?>" selected><?= $username;?></option>
                    <?php }else{;?>
                        <option value="<?= $id;?>"><?= $username;?></option>
                    <?php };?>
                <?php endforeach;?>
                </select>
                <div class="help-block"></div>
            </div>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'is_ads')->dropDownList([ '' => "是否投放" ,'0' => '未投放','1'=>'已投放'] ) ?>

            <?= $form->field($model, 'disable')->dropDownList([ '' => "是否已下架" ,'0' => '未下架','1'=>'已下架'] ) ?>

            <?= $form->field($model, 'is_group')->dropDownList([ '' => "是否组合产品" ,'0' => '否','1'=>'是'] ) ?>

            <?= $form->field($model, 'price') ?>

            <div class="row form-group">
                <div class="col-md-12"><label class="control-label">创建时间</label></div>
                <div class="col-md-6">
                    <?php
                    echo DateTimePicker::widget([
                        'name' => 'order_time_begin',
                        'options' => ['placeholder' => '创建范围查询-开始', 'style' => 'width:200px;'],
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
                        'options' => ['placeholder' => '创建范围查询-结束', 'style' => 'width:200px;'],
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
        </div>
    </div>

    <div class="form-group">
        <button type="button" onclick="$('#search-box').toggle()" class="btn btn-default">高级搜索</button>
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
