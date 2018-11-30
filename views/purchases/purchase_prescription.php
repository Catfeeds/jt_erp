<?php

use yii\helpers\Html;
use kartik\datetime\DateTimePicker;

$this->title = '采购单时效导出';
?>
<div class="orders-index box box-primary">
    <button type="submit" class="btn btn-primary back" style="margin:10px;">返回</button>
    <div class="box-body table-responsive">
        <?php
        echo Html::beginForm('purchase-prescription','post', ["enctype" => "multipart/form-data"]);
        ?>
        <div class="col-md-6">
            <div class="row form-group">
                <div class="col-md-12"><label class="control-label">发货时间</label></div>
                <div class="col-md-6">
                    <?php
                    echo DateTimePicker::widget([
                        'name' => 'time_begin',
                        'options' => ['placeholder' => '发货范围查询-开始', 'style' => 'width:200px;'],
                        //注意，该方法更新的时候你需要指定value值
                        'value' => $time_begin,
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
                        'name' => 'time_end',
                        'options' => ['placeholder' => '发货范围查询-结束', 'style' => 'width:200px;'],
                        //注意，该方法更新的时候你需要指定value值
                        'value' => $time_end,
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
    <div style="margin: 10px;">
        <?php
        echo Html::submitButton('导出',['class'=>'btn btn-primary save']);
        echo Html::endForm();
        ?>
    </div>
    <?php if($error){?>
        <div style="color:red;"><?= $error;?></div>
    <?php }?>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    $(".back").on('click',function () {
        window.location.assign('/purchases/index');
    });
</script>