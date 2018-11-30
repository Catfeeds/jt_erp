<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUpload;


/* @var $this yii\web\View */
/* @var $model app\models\ProductsBase */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-base-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <?php if($model->spu) :?>
        <?= $form->field($model, 'spu')?>
        <?php endif;?>
        <?= $form->field($model, 'categorie')->dropdownList($categories)?>

        <?= $form->field($model, 'sex')->dropdownList([0 => "通用", 1 => "男", 2 => "女"])  ?>

        <div class="form-group field-orderssearch-lc">
            <label class="control-label" for="orderssearch-spu">产品类型</label>&nbsp;&nbsp;&nbsp;<span style="color: maroon"></span><br>
            <select name="product_type" style="height: 32px;width: 360px;">
                <?php foreach (\app\models\ProductsBase::$product_type_arr as $key => $type){?>
                    <?php if($key == $model->product_type){?>
                        <option value="<?= $key;?>" selected="selected"><?= $type;?></option>
                    <?php }else{?>
                        <option value="<?= $key;?>"><?= $type;?></option>
                    <?php };?>
                <?php }?>
            </select>

            <div class="help-block"></div>
        </div>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'en_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'image')->hiddenInput() ?>
        <?= FileUpload::widget([
            'model' => $model,
            'attribute' => 'images_json',
            'url' => ['products-base/image-upload', 'attribute' => 'images_json'], // your url, this is just for demo purposes,
            'options' => ['accept' => 'image/*'],
            'clientOptions' => [
                'maxFileSize' => 2000000
            ],     // Also, you can specify jQuery-File-Upload events
            // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                                if($(\'#thumbnail\').find(\'img\').length >= 1) {
                                $(\'#thumbnail\').find(\'img\').remove();
                                $(\'#img_vbox\').find(\'input\').remove();
                                }
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
            if($model->image):
                $img = $model->image;
                //foreach($image as $img):
                    ?>
                    <img width="100" onclick="jQuery('#Img<?=md5($img);?>').remove();jQuery(this).remove();" height="100" src="<?= $img;?>"/>
                <?php //endforeach;?>
            <?php endif;?>
        </div>
        <div id="img_vbox">
            <?php
            if($model->image):
//                $images = json_decode($model->image);
                $img = $model->image;
                //foreach(image as $img):
                    ?>
                    <input type="hidden" name="image_val[]" id="Img<?=md5($img);?>" value="<?=$img;?>"/>
                <?php //endforeach;?>
            <?php endif;?>
        </div>
        <br>

        <?= $form->field($model, 'open')->dropdownList([1 => "组内可见", 2 => "所有人可见"]) ?>

        <?= $form->field($model, 'declaration_hs')->textInput(['maxlength' => true]) ?>


    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
