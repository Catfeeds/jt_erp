<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUpload;

/* @var $this yii\web\View */
/* @var $model app\models\Websites */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="websites-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">基本信息</a></li>
        <li><a href="#tab_2" data-toggle="tab">图片与变体</a></li>
        <li><a href="#tab_3" data-toggle="tab">产品描述</a></li>
        <li><a href="#tab_4" data-toggle="tab">产品属性</a></li>
        <li><a href="#tab_6" data-toggle="tab">推荐产品与套餐设置</a></li>
        <li><a href="#tab_5" data-toggle="tab">统计代码</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="box-body table-responsive">
                <?= $form->field($model, 'spu')->textInput(['maxlength' => true, 'readonly'=>true]) ?>
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'sale_price')->textInput() ?>

                <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'sale_end_hours')->textInput() ?>

                <?= $form->field($model, 'sale_city')->dropDownList(['' => '选择销售地区'] + $model->country) ?>

                <?= $form->field($model, 'domain')->dropDownList($model->domains) ?>

                <?= $form->field($model, 'host')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'theme')->dropDownList(['' => ''] + $model->templates) ?>

                <?= $form->field($model, 'is_ads')->dropDownList(['0' => '未投放','1'=>'已投放']) ?>

                <?= $form->field($model, 'sale_info')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'next_price')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="tab-pane" id="tab_2">
            <div class="box-body table-responsive">
                <?= $form->field($model, 'designer')->dropDownList(['' => '设计师'] + $model->getUsersByRole('设计师')) ?>

                <?= $form->field($model, 'images')->hiddenInput() ?>
                <?= FileUpload::widget([
                    'model' => $model,
                    'attribute' => 'images_json',
                    'url' => ['websites/image-upload', 'attribute' => 'images_json'], // your url, this is just for demo purposes,
                    'options' => ['accept' => 'image/*'],
                    'clientOptions' => [
                        'maxFileSize' => 2000000
                    ],
                    // Also, you can specify jQuery-File-Upload events
                    // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
                    'clientEvents' => [
                        'fileuploaddone' => 'function(e, data) {
                                res = JSON.parse(data.result);
                                $.each(res.files, function(index, f){
                                     jQuery("<img onclick=\'jQuery(\"#Img" + f.name.replace(/\//g, "").split(".").join("") + "\").remove();jQuery(this).remove();\' width=100 height=100 src=\'" + f.url + "\' />").appendTo("#thumbnail");
                                     jQuery("<input type=\'hidden\' value=\'" + f.url + "\' id=\'Img" + f.name.replace(/\//g, "").split(".").join("") + "\' name=\'image_val[]\'>").appendTo("#img_vbox");})
                            }',
                        'fileuploadfail' => 'function(e, data) {
                                console.log(data);
                            }',
                    ],
                ]); ?>
                <div id="thumbnail" style="height: 110px;border:solid 1px #6f8c49;">
                    <?php
                    if($model->images):
                        $images = json_decode($model->images);
                        foreach($images as $img):
                            ?>
                            <img width="100" onclick="jQuery('#Img<?=md5($img);?>').remove();jQuery(this).remove();" height="100" src="<?= $img;?>"/>
                        <?php endforeach;?>
                    <?php endif;?>
                </div>
                <div id="img_vbox">
                    <?php
                    if($model->images):
                        $images = json_decode($model->images);
                        foreach($images as $img):
                            ?>
                            <input type="hidden" name="image_val[]" id="Img<?=md5($img);?>" value="<?=$img;?>"/>
                        <?php endforeach;?>
                    <?php endif;?>
                </div>
                <br>

                <?= $form->field($model, 'size')->textInput(['maxlength' => true])->hint('多个尺寸用,号隔开.如果要补差价在尺寸后跟上#差价，如S#100,M#200') ?>

                <?= $form->field($model, 'product_style_title')->textInput(['maxlength' => true]) ?>

                <div>
                    <label>多属性图(点击图片可以删除)</label>

                    <?= FileUpload::widget([
                        'model' => $model,
                        'attribute' => 'product_style',
                        'url' => ['websites/image-upload', 'attribute' => 'product_style'], // your url, this is just for demo purposes,
                        'options' => ['accept' => 'image/*'],
                        'clientOptions' => [
                            'maxFileSize' => 2000000
                        ],
                        // Also, you can specify jQuery-File-Upload events
                        // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
                        'clientEvents' => [
                            'fileuploaddone' => 'function(e, data) {
                                res = JSON.parse(data.result);
                                $.each(res.files, function(index, f){
                                    jQuery("<div><img onclick=\'jQuery(this).parent().remove()\' width=100 height=100 src=\'" + f.url + "\' /> 颜色:<input type=\'text\' id=\'Sname" + f.name.replace(/\//g, "").split(".").join("") + "\' name=\'style_name[]\'> 差价:<input type=\'text\' id=\'Sprice" + f.name.replace(/\//g, "").split(".").join("") + "\' name=\'style_price[]\'></div>").appendTo("#styles");
                                    jQuery("<input type=\'hidden\' value=\'" + f.url + "\' id=\'Style" + f.name.replace(/\//g, "").split(".").join("") + "\' name=\'style_image[]\'>").appendTo("#styles_vbox");
                                    jQuery("<input type=\'hidden\'  value=\"\" id=\'Scolor" + f.name.replace(/\//g, "").split(".").join("") + "\' name=\'style_color[]\' class=\'poiu\'>").appendTo("#styles_vbox");
                                    jQuery(function(){
                                        var i;
                                        if (isNaN($(".poiu").eq(-2).val())){
                                            i=1;
                                            $(".poiu").last().val(i);
                                        }else{
                                            i = parseInt($(".poiu").eq(-2).val())+1;
                                            $(".poiu").last().val(i);
                                        }
                                    });
               
                            })}',
                            'fileuploadfail' => 'function(e, data) {
                                console.log(data);
                            }',
                        ],
                    ]); ?>

                    <div id="styles" style="min-height: 110px;border:solid 1px #6f8c49;">
                        <?php
                        if($model->product_style):
                            $images = json_decode($model->product_style);
                            foreach($images as $img):
                                ?>
                                <div>
                                    <img width="100" onclick="jQuery(this).parent().children().remove().parent().remove();" height="100" src="<?= $img->image;?>"/>
                                    颜色:<input type="text" name="style_name[]" id="Sname<?=md5($img->image)?>" value="<?=$img->name;?>" >
                                    差价:<input type="text" name="style_price[]" id="Sprice<?=md5($img->image)?>" value="<?=$img->add_price;?>" >
                                    <input type="hidden" name="style_image[]" id="Style<?=md5($img->image);?>" value="<?=$img->image;?>"/>
                                    <input type="hidden" name="style_color[]" id="Scolor<?=md5($img->image);?>" class="poiu" value=""/>
                                </div>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                    <div id="styles_vbox">
                    </div>

                </div>


            </div>
        </div>
        <div class="tab-pane" id="tab_3">
            <div class="box-body table-responsive">
                <?= $form->field($model,'info')->widget('kucha\ueditor\UEditor',[
                        'clientOptions' => [
                                'initialFrameWeight' => '640'
                        ]
                ]);?>
            </div>
        </div>

        <div class="tab-pane" id="tab_4">
            <div class="box-body table-responsive">
                <?= $form->field($model,'additional')->widget('kucha\ueditor\UEditor',[]);?>
            </div>
        </div>

        <div class="tab-pane" id="tab_5">
            <div class="box-body table-responsive">
                <?= $form->field($model, 'cloak')->dropDownList([0=>'否', 1=>'是']) ?>

                <?= $form->field($model, 'cloak_url')->textInput() ?>

                <?= $form->field($model, 'facebook')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'google')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'other')->textarea(['rows' => 6]) ?>
            </div>
        </div>

        <div class="tab-pane" id="tab_6">
            <div class="box-body table-responsive">
                <?= $form->field($model, 'related_id')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'is_group')->dropDownList([0=>'否', 1=>'是']) ?>
                <div id="group_box">
                    <div>
                        套餐名称：<input name="group_title[]">
                        套餐价格：<input name="group_price[]">
                        套餐产品：<input name="group_products[]">
                        <button type="button" onclick="addGroup()">+</button>
                    </div>
                    <?php
                    if($model->id):
                        $groups = \app\models\WebsitesGroup::findAll(['website_id' => $model->id]);
                        foreach ($groups as $group):
                    ?>
                    <div>
                        套餐名称：<input name="group_title[]" value="<?=$group->group_title;?>">
                        套餐价格：<input name="group_price[]" value="<?=$group->group_price;?>">
                        套餐产品：<input name="group_products[]" value="<?=$group->website_ids;?>">
                        <button type="button" onclick="delGroup(this)">+</button>
                    </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>

            </div>
        </div>
        <style>
            #group_box{
                display: none;
            }
            #group_box div{
                margin-bottom: 10px;
            }
        </style>
        <?php
        $js = <<<JS
            jQuery(function(){
                if(jQuery("#websites-is_group").val() == 1)
                    {
                        jQuery("#group_box").show();
                    }
                jQuery("#websites-is_group").change(function () {
                    if(jQuery(this).val() == 1)
                    {
                        jQuery("#group_box").show();
                    }
                });
            });
JS;
        $this->registerJs($js);
        ?>
        <script>

            function addGroup(){
                $("#group_box").append('<div>\n' +
                    '                        套餐名称：<input name="group_title[]">\n' +
                    '                        套餐价格：<input name="group_price[]">\n' +
                    '                        套餐产品：<input name="group_products[]">\n' +
                    '                        <button onclick="delGroup(this)" type="button">-</button>\n' +
                    '                    </div>');
            }
            function delGroup(obj){
                $(obj).parent().remove();
            }
        </script>

    </div>

    <div class="box-footer">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
