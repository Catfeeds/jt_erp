<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUpload;


/* @var $this yii\web\View */
/* @var $model app\models\ProductsVariant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-variant-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">基本信息</a></li>
        <?php if(Yii::$app->controller->action->id == 'update') :?>
        <li><a href="#tab_2" data-toggle="tab">变体选择供应商</a></li>
        <?php endif;?>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
             <div class="box-body table-responsive">
                 <?php if($model->sku) :?>
                     <?= $form->field($model, 'sku')?>
                 <?php endif;?>

            <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'size')->textInput(['maxlength' => true])->hint('一些可添加多个尺寸，用,号分开') ?>
            <?= $form->field($model, 'image')->hiddenInput() ?>
            <?= FileUpload::widget([
                'model' => $model,
                'attribute' => 'images_json',
                'url' => ['products-variant/image-upload', 'attribute' => 'images_json'], // your url, this is just for demo purposes,
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
        </div>
        </div>
        <div class="tab-pane" id="tab_2">



            <div class="box-body table-responsive" >
                <a id="add_suppliers" ><span class="btn btn-info"> 添加供应商</span></a>
                <table class="table table-bordered" id="product_supplier_list">
                    <th>供应商</th>
                    <th>采购链接</th>
                    <th>最小起订量</th>
                    <th>采购价</th>
                    <th>发货周期</th>
                    <th></th>
                <?php   if($product_supplier) :$i=0;foreach($product_supplier as $key=>$list):?>
                <tr class="div_supplier">
                    <td>
                <select name="products_suppliers[<?php echo $i;?>][supplier_id]">
                    <?php   foreach($suppliers as $supp_id=>$supp):?>
                    <option value="<?php echo $supp_id;?>" <?php if($list['supplier_id'] == $supp_id):?>selected<?php endif;?>><?php echo $supp;?></option>
                    <?php endforeach;?>
                </select>
                    </td>
                    <td>
                    <input type="text" class="input-small" name="products_suppliers[<?php echo $i;?>][url]" value="<?php echo $list['url'];?>" placeholder="采购链接">
                    </td>
                    <td>
                    <input type="text" class="input-small" name="products_suppliers[<?php echo $i;?>][min_buy]" value="<?php echo $list['min_buy'];?>" placeholder="最小起订量">
                    </td>
                    <td>
                    <input type="text" class="input-small" name="products_suppliers[<?php echo $i;?>][price]" value="<?php echo $list['price'];?>" placeholder="采购价">
                    </td>
                    <td>
                    <input type="text" class="input-small" name="products_suppliers[<?php echo $i;?>][deliver_time]" value="<?php echo $list['deliver_time'];?>" placeholder="发货周期">
                    <input type="hidden" class="input-small" name="products_suppliers[<?php echo $i;?>][id]" value="<?php echo $list['id'];?>" placeholder="">
                    </td>
                    <td><?php if($i !=0):?><a onclick="delete_suppliers(this)">删除</a><?php endif;?></td>
                </tr>
                <?php $i++;endforeach;endif;?>
            </table>

        </div>
    </div>

    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function delete_suppliers(e){
        $(e).parent().parent().remove();

    }
<?php $this->beginBlock('suppliers_js') ?>
    var supplier_num = $('.div_supplier').length ? $('.div_supplier').length :0;

    $('#add_suppliers').click(function () {
        var div_supp = '<tr class="div_supplier">';
        div_supp += '<td><select name="products_suppliers['+supplier_num+'][supplier_id]">';
        <?php   foreach($suppliers as $supp_id=>$supp):?>
        div_supp += '<option value="<?php echo $supp_id;?>" <?php if($list['supplier_id'] == $supp_id):?>selected<?php endif;?>><?php echo $supp;?></option>';
        <?php endforeach;?>
        div_supp += '</select></td>';
        div_supp += '<td><input type="text" class="input-small" name="products_suppliers['+supplier_num+'][url]" value="" placeholder="采购链接"></td>';
        div_supp += '<td><input type="text" class="input-small" name="products_suppliers['+supplier_num+'][min_buy]" value="" placeholder="最小起订量"></td>';
        div_supp += '<td><input type="text" class="input-small" name="products_suppliers['+supplier_num+'][price]" value="" placeholder="采购价"></td>';
        div_supp += '<td><input type="text" class="input-small" name="products_suppliers['+supplier_num+'][deliver_time]" value="" placeholder="发货周期"></td>';
    div_supp += '<td><a onclick="delete_suppliers(this)">删除</a></td>';
    div_supp += '</tr>';
        $('#product_supplier_list').append(div_supp);
        supplier_num++;
    });

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['suppliers_js']); ?>
</script>
