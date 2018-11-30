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

        <div class="box-body table-responsive" >
            <a id="add_suppliers" ><span class="btn btn-info"> 添加供应商</span></a>
            <table class="table table-bordered" id="product_supplier_list">
                <th>供应商</th>
                <th>采购链接</th>
                <th>最小起订量</th>
                <th>采购价</th>
                <th>发货周期</th>
                <th></th>
            </table>

        </div>
        <div class="box-body table-responsive" >
            <div>
                <span style="margin-left: 10px;color: red">采购链接剪切板:</span>&nbsp;&nbsp;
                <input type="text" name="url_content" value="" style="width: 360px;height: 36px;" placeholder="请输入采购链接">
                &nbsp;&nbsp;<span class="btn btn-primary add">添加</span>
            </div>
            <table class="table table-bordered" id="body">
                <th><input type="checkbox" id="chk_item" value=""></th>
                <th>sku</th>
                <th>供应商</th>
                <th>采购链接</th>
                <th>最小起订量</th>
                <th>采购价</th>
                <th>发货周期</th>
                <?php   if($product_supplier) :$i=0;foreach($product_supplier as $key=>$list):?>
                    <tr>
                        <td>
                            <input type="checkbox" name="chk_item">
                        </td>
                        <td>
                            <?php echo $list['sku'];?>
                            <input type="hidden" class="input-small" name="update_products_suppliers[<?php echo $i;?>][sku]" value="<?php echo $list['sku'];?>" placeholder="">
                        </td>
                        <td>
                            <select name="update_products_suppliers[<?php echo $i;?>][supplier_id]">
                                <?php   foreach($suppliers as $supp_id=>$supp):?>
                                    <option value="<?php echo $supp_id;?>" <?php if($list['supplier_id'] == $supp_id):?>selected<?php endif;?>><?php echo $supp;?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="input-small" name="update_products_suppliers[<?php echo $i;?>][url]" value="<?php echo $list['url'];?>" placeholder="采购链接">
                        </td>
                        <td>
                            <input type="text" class="input-small" name="update_products_suppliers[<?php echo $i;?>][min_buy]" value="<?php echo $list['min_buy'];?>" placeholder="最小起订量">
                        </td>
                        <td>
                            <input type="text" class="input-small" name="update_products_suppliers[<?php echo $i;?>][price]" value="<?php echo $list['price'];?>" placeholder="采购价">
                        </td>
                        <td>
                            <input type="text" class="input-small" name="update_products_suppliers[<?php echo $i;?>][deliver_time]" value="<?php echo $list['deliver_time'];?>" placeholder="发货周期">
                            <input type="hidden" class="input-small" name="update_products_suppliers[<?php echo $i;?>][id]" value="<?php echo $list['id'];?>" placeholder="">
                        </td>
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

    $(document).on('click','#chk_item',function()
    {
        var flag = $("#chk_item").is(':checked');
        $("input[name='chk_item']:checkbox").each(function() {
            $(this).prop("checked", flag);
        });
    });


    $('.add').on('click',function () {
        var url_content = $("input[name='url_content']").val();
        if (!url_content)
        {
            alert('请输入采购链接');
        }
        else
        {
            //获取选中的记录的id
            $("input[name='chk_item']:checkbox").each(function()
            {
                var flag = $(this).is(':checked');
                if (flag)
                {
                    $(this).parents('tr').children().eq(3).children().val(url_content);
                }
            });
        }
    });

    <?php $this->endBlock() ?>
    <?php $this->registerJs($this->blocks['suppliers_js']); ?>
</script>
