<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\ProductsBaseSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $cats_arr yii\widgets\ActiveForm */
?>

<div class="products-base-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row" style="display: none" id="search-box">
        <div class="col-md-6">
            <?= $form->field($model, 'id')->textArea(['rows' => '5']); ?>
            <div class="row form-group">
                <div class="col-md-12"><label class="control-label">添加时间</label></div>
                <div class="col-md-6">
                    <?php
                    echo DateTimePicker::widget([
                        'name' => 'order_time_begin',
                        'options' => ['placeholder' => '添加范围查询-开始', 'style' => 'width:200px;'],
                        //注意，该方法更新的时候你需要指定value值
                        'value' => $orderTimeBegin,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'startView' => 2,    //其实范围（0：日  1：天 2：年）
                            'minView' => 2,  //最大选择范围（年）
                            'maxView' => 2,  //最小选择范围（年）
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    echo DateTimePicker::widget([
                        'name' => 'order_time_end',
                        'options' => ['placeholder' => '添加范围查询-结束', 'style' => 'width:200px;'],
                        //注意，该方法更新的时候你需要指定value值
                        'value' => $orderTimeEnd,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'startView' => 2,    //其实范围（0：日  1：天 2：年）
                            'minView' => 2,  //最大选择范围（年）
                            'maxView' => 2,  //最小选择范围（年）
                        ]
                    ]);
                    ?>
                </div>
            </div>

        <?php echo $form->field($model, 'product_type')->dropDownList(['' => "请选择", '1' => '普货', '2' => '特货']) ?>
        <?php echo $form->field($model, 'sex')->dropDownList(['' => '请选择','0' => "通用", '1' => '男', '2' => '女']) ?>

        </div>
        <div class="col-md-6">
            <?php  echo $form->field($model, 'en_name') ?>

            <?php  echo $form->field($model, 'title') ?>

            <?php  echo $form->field($model, 'spu') ?>
            <?php
            $userModel = new \app\models\User();
            ?>
            <?php echo $form->field($model, 'uid')->dropDownList(['' => '产品开发人'] + $userModel->getUsers()) ?>
        </div>
    </div>
</div>

<div class="form-group">
    <button type="button" onclick="$('#search-box').toggle()" class="btn btn-default">高级搜索</button>
    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>
