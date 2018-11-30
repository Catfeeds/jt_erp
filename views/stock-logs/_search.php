<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\StockLogsSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $country yii\widgets\ActiveForm */
?>

<div class="stock-logs-search" >

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row" style="display: none" id="search-box">
        <div class="col-md-6">
            <?= $form->field($model, 'order_id')->textArea(['rows' => '5']); ?>

            <?= $form->field($model, 'sku') ?>

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

            <?php echo $form->field($model, 'type')->dropDownList([''=>'出入库类型']+$model->status_array) ?>

            <?php
            echo DateTimePicker::widget([
                'name' => 'LocationLogSearch[start_date]',
                'options' => ['placeholder' => '出入库范围查询-开始', 'style' => 'width:200px;'],
                //注意，该方法更新的时候你需要指定value值
                'value' => $model->start_date,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'startView'=>2,    //其实范围（0：日  1：天 2：年）
                    'minView'=>2,  //最大选择范围（年）
                    'maxView'=>2,  //最小选择范围（年）
                ]
            ]);
            echo "<br>";
            ?>
            <?php
            echo DateTimePicker::widget([
                'name' => 'LocationLogSearch[end_date]',
                'options' => ['placeholder' => '出入库范围查询-结束', 'style' => 'width:200px;'],
                //注意，该方法更新的时候你需要指定value值
                'value' => $model->end_date,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'startView'=>2,    //其实范围（0：日  1：天 2：年）
                    'minView'=>2,  //最大选择范围（年）
                    'maxView'=>2,  //最小选择范围（年）
                ]
            ]);
            echo "<hr>";
            ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <button type="button" onclick="$('#search-box').toggle()" class="btn btn-default">高级搜索</button>
    </div>

    <?php ActiveForm::end(); ?> 

</div>
<script src=""></script>
<script>

</script>
