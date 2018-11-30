<?php
/* @var $this yii\web\View */
/* @var $model app\models\Orders */
/* @var $data_arr app\models\Orders */
/* @var $spu_arr app\models\Orders */
/* @var $order_info app\models\Orders */
?>
<style>
    th {text-align:center}
    td {text-align:center}
    input {
        border: 2px solid #ccc;
        padding: 7px 0px;
        border-radius: 3px; /*css3属性IE不支持*/
        padding-left:5px;
        text-align:center;
    }
    #order_info input{
        width: 320px;
    }
    select {
        width: 150px;
        height: 28px;
    }
    .submit{
        cursor:pointer;
    }

     .zoom {
         -webkit-transition: all 0.35s ease-in-out;
         -moz-transition: all 0.35s ease-in-out;
         transition: all 0.35s ease-in-out;
         cursor: -webkit-zoom-in;
         cursor: -moz-zoom-in;
         cursor: zoom-in;
     }

    .zoom:hover,
    .zoom:active,
    .zoom:focus {
        -ms-transform: scale(2.5);
        -moz-transform: scale(2.5);
        -webkit-transform: scale(2.5);
        -o-transform: scale(2.5);
        transform: scale(2.5);
        position: relative;
        z-index: 1000;
    }
</style>
<div class="orders-item-form box box-primary">
    <div class="box-body table-responsive">
        <div class="form-actions">
            <button type="submit" class="btn btn-primary save" style="margin: 10px;">保存</button>
            <button type="submit" class="btn btn-primary back" style="margin-bottom: 10px;margin-right: 10px;margin-top: 10px;">返回</button>
        </div>
        <ul class="nav nav-tabs">
            <h3>订单基本信息</h3>
        </ul>
        <div id="order_info">
            <div class="span3">
                <table class="table table-bordered" width="510" border="2" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="180">订单ID</th>
                        <td>
                            <input type="text" value="<?= $order_info['id'];?>" readonly><span style="color: red;">*</span>
                            <input type="hidden" id="order_id" value="<?= $order_info['id']?>">
                        </td>
                        <th>订单状态</th>
                        <td><input type="text" value="<?= \app\models\Orders::$status_arr[$order_info['status']]?>"><input type="hidden" name="id_order_status" value="<?= $order_info['status'];?>" readonly><span style="color: red;">*</span></td>
                    </tr>
                    <tr>
                        <th>站点</th><td><input type="text" name="" value="<?= $order_info['website_id'];?>" readonly><span style="color: red;">*</span></td>
                        <th>结算状态</th><td><input type="text" name="" value="<?= \app\models\Orders::$money_status_arr[$order_info['money_status']];?>" readonly><span style="color: red;">*</span></td>
                    </tr>
                    <tr>
                        <th>姓名</th>
                        <td><input type="text" name="name" value="<?= $order_info['name'];?>"></td>
                        <th>电话</th>
                        <td><input type="text" name="mobile" value="<?= $order_info['mobile']?>"></td>
                    </tr>
                    <tr>
                        <th>邮箱</th>
                        <td><input type="text" name="email" value="<?= $order_info['email']?>"></td>
                        <th>国家</th><td><input type="text" name="country" value="<?= $order_info['country'];?>"></td>
                    </tr>
                    <tr>
                        <th>省份</th><td><input type="text" name="district" value="<?= $order_info['district'];?>"></td>
                        <th>城市</th><td><input type="text" name="city" value="<?= $order_info['city'];?>"></td>
                    </tr>
                    <tr>
                        <th>区域</th><td><input type="text" name="area" value="<?= $order_info['area'];?>"></td>
                        <th>地址</th><td><input type="text" name="address" value="<?= $order_info['address']?>">
                    </tr>
                    <tr>
                        <th>购买数量</th><td><input type="text" name="" value="<?= $order_info['qty'];?>" readonly><span style="color: red;">*</span></td>
                        <th>总价</th><td><input type="text" name="" value="<?= $order_info['total']?>" readonly><span style="color: red;">*</span></td>
                    </tr>
                    <tr>
                        <th>邮编</th><td><input type="text" name="post_code" value="<?= $order_info['post_code'];?>"></td>
                        <th>操作人备注</th><td><textarea name="comment_u" id="" cols="30" rows="10" style="resize: none;width: 320px;height: 72px;"><?= $order_info['comment_u'];?></textarea></td>
                    </tr>
                    <tr><th>留言</th><td><textarea name="" id="" cols="30" rows="10" style="resize: none;width: 320px;height: 72px;" disabled><?= $order_info['comment'];?></textarea><span style="color: red;">*</span></td>
                        <th>下单时间</th><td><input type="text" value="<?= $order_info['create_date'];?>" readonly><span style="color: red;">*</span></td>
                    </tr>
                </table>
            </div>
        </div>
        <ul class="nav nav-tabs">
            <h3>订单详情信息</h3>
        </ul>
        <div id="tableLayer">
            <fieldset>
                <table id="sku-list" class="table table-hover table-bordered table-list order-table" >
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>产品名称</th>
                        <th>spu</th>
                        <th>图片</th>
                        <th>color</th>
                        <th>size</th>
                        <th>提取属性</th>
                        <th>sku</th>
                        <th>价格</th>
                        <th>数量</th>
                    </tr>
                    </thead>
                    <tbody id="body">
                    <?php foreach ($model as $key => $value) :?>
                    <tr>
                        <td><?= $key+1;?></td>
                        <td>
                            <input type="text" name="title_<?= $key;?>" value="<?= $value['title'];?>" readonly>
                            <input type="hidden" name="id_<?= $key;?>" value="<?= $value['id'];?>">
                        </td>
                        <td><input type="text" name="spu_<?= $key;?>" value="<?= $value['spu'];?>" readonly></td>
                        <td>
                            <img class="zoom" name="image_<?= $key;?>" src="http://admin.kingdomskymall.net.<?= $value['image']?>" width="50">
                            <input type="hidden" name="image_<?= $key;?>" value="<?= $value['image']?>">
                        </td>
                        <td>
                            <select name="color_<?= $key;?>">
                                <option value="<?php echo $value['color']?>"><?php echo $value['color']?$value['color']:'**'?></option>
                            </select>
                        <td>
                            <select name="size_<?= $key;?>">
                                <option value="<?php echo $value['size']?>"><?php echo $value['size']?$value['size']:'**'?></option>
                            </select>
                        </td>
                        <td><span class="submit_no">★</span></td>
                        <td>
                            <select name="sku_<?= $key;?>">
                                <option value="<?php echo $value['sku']?>"><?php echo $value['sku']?$value['sku']:'**'?></option>
                            </select>
                        </td>
                        <td><input type="number" name="price_<?= $key;?>" value="<?= $value['price']?>" <?= $order_info['status']!=1?'disabled':'';?>></td>
                        <td><input type="number" name="qty_<?= $key;?>" value="<?= $value['qty']?>" <?= $order_info['status']!=1?'disabled':'';?>></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </fieldset>
        </div>
        <div class="form-actions">
            <div style="float: right;margin-right: 80px;display: <?= $order_info['status']!=1?'none':'block';?>">
                <button type="submit" class="btn btn-primary one_more">添加</button>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span>
                    <select name="spu_select">
                        <option value="">请选择spu</option>
                    <?php foreach ($spu_arr as $v) :?>
                        <option value="<?= $v?>"><?php echo $v;?></option>
                    <?php endforeach;?>
                    </select>
                </span>
            </div>

        </div>
    </div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    //页面的数据的修改
    $(".save").on('click',function () {
        //获取订单基本信息
        var name = $("input[name='name']").val();
        var mobile = $("input[name='mobile']").val();
        var email = $("input[name='email']").val();
        var country = $("input[name='country']").val();
        var district = $("input[name='district']").val();
        var city = $("input[name='city']").val();
        var area = $("input[name='area']").val();
        var address = $("input[name='address']").val();
        var comment_u = $("textarea[name='comment_u']").val();
        var post_code = $("input[name='post_code']").val();
        var id_order_status = $("input[name='id_order_status']").val();
        var order_id = $("#order_id").val();
        var update_data = "[";
        $("#body tr").each(function () {
            var id = $(this).find("input[name^='id_']").val();
            var sku = $(this).find("select[name^='sku_']").val();
            var size = $(this).find("select[name^='size_']").val();
            var color = $(this).find("select[name^='color_']").val();
            var price = $(this).find("input[name^='price_']").val();
            var qty = $(this).find("input[name^='qty_']").val();
            var image = $(this).find("input[name^='image_']").val();
            update_data += '{"id":'+id+',"size":"'+size+'","color":"'+color+'","sku":"'+sku+'","qty":'+qty+',"price":'+price+',"image":"'+image+'"},';
        });
        //去掉最后一个","
        var reg = /,$/gi;
        update_data = update_data.replace(reg, "");
        update_data += "]";
        $.ajax({
            url:"/orders-audit/save-order-item",
            type:'POST',
            dataType:'json',
            data:{
                'update_data':update_data,
                'id_order':order_id,
                'name':name,
                'mobile':mobile,
                'email':email,
                'country':country,
                'district':district,
                'city':city,
                'area':area,
                'address':address,
                'post_code':post_code,
                'id_order_status':id_order_status,
                'comment_u':comment_u
            },
            success:function(data){
                console.log(data);
                window.location.reload();
            }
        });
    });

    //color,size联动
    $("#sku-list").on('click',".submit",function () {
        //获取该行的spu,color，size数据
        var number = $(this).parents("tr").children().eq(0).html()-1;
        var spu = $(this).parents("tr").find("input[name^='spu_']").val();
        var color = $(this).parents("tr").find("select[name^='color_']").val();
        var size = $(this).parents("tr").find("select[name^='size_']").val();
        //异步请求sku数据
        $.ajax({
            url:"/orders-audit/get-sku-by-attr",
            type:'POST',
            dataType:'json',
            data:{
                'spu':spu,
                'color':color,
                'size':size
            },
            success:function(data){
                if (data['res'])
                {
                    $("select[name='sku_"+number+"']").val(data['sku']);
                    $("img[name='image_"+number+"']").attr('src',"http://admin.kingdomskymall.net."+data['image']);
                    $("input[name='image_"+number+"']").val(data['image']);
                }
                else
                {
                    alert('返回数据异常');
                    window.location.reload(); //数据获取异常,页面重新刷新,避免错误数据提交
                }
            }
        });
    });

//    $("#sku-list").on('change',"select[name^='color_']",function () {
//        //获取该行的spu,color，size数据
//        var number = $(this).parents("tr").children().eq(0).html()-1;
//        var spu = $(this).parents("tr").find("input[name^='spu_']").val();
//        var color = $(this).parents("tr").find("select[name^='color_']").val();
//        var size = $(this).parents("tr").find("select[name^='size_']").val();
//        //异步请求sku数据
//        $.ajax({
//            url:"/orders-audit/get-sku-by-attr",
//            type:'POST',
//            dataType:'json',
//            data:{
//                'spu':spu,
//                'color':color,
//                'size':size
//            },
//            success:function(data){
//                if (data['res'])
//                {
//                    $("select[name='sku_"+number+"']").val(data['sku']);
//                    $("img[name='image_"+number+"']").attr('src',"http://admin.kingdomskymall.net."+data['image']);
//                    $("imput[name='image_"+number+"']").val(data['image']);
//                }
//                else
//                {
//                    alert('返回数据异常');
////                    window.location.reload(); //数据获取异常,页面重新刷新,避免错误数据提交
//                }
//            }
//        });
//    });

    //sku联动获取color,size的值
    $("#sku-list").on("change","select[name^='sku_']",function () {
        //获取sku值
        var number = $(this).parents("tr").children().eq(0).html()-1;
        var sku = $(this).parents("tr").find("select[name^='sku_']").val();
        //异步请求color和size数据
        $.ajax({
            url:"/orders-audit/get-attr-by-sku",
            type:'POST',
            dataType:'json',
            data:{
                'sku':sku
            },
            success:function(data){
                if (data['res'])
                {
                    $("select[name='color_"+number+"']").val(data['color']);
                    $("select[name='size_"+number+"']").val(data['size']);
                    $("img[name='image_"+number+"']").attr('src',"http://admin.kingdomskymall.net."+data['image']);
                    $("input[name='image_"+number+"']").val(data['image']);
                    $("input[name='spu_"+number+"']").val(data['spu']);
                    $("input[name='title_"+number+"']").val(data['title']);
                }
                else
                {
                    alert('返回数据异常');
                }
            }
        });
    });

    //价格和数量设置限制
    $("#sku-list").on('change',"input[name^='price_']",function(){
        var number = $(this).parents("tr").children().eq(0).html()-1;
        var price = $(this).parents("tr").find("input[name^='price_"+number+"']").val();
        if (price<0 || !price)
        {
            $("input[name='price_"+number+"']").val(0);
        }
    });

    $("#sku-list").on('change',"input[name^='qty_']",function(){
        var number = $(this).parents("tr").children().eq(0).html()-1;
        var qty = $(this).parents("tr").find("input[name='qty_"+number+"']").val();
        if (qty <0 || !qty)
        {
            $("input[name='qty_"+number+"']").val(0);
        }
    });

    //添加更多订单详情
    $(".one_more").on('click',function () {
        var spu = $("select[name='spu_select']").val();
        if (!spu)
        {
            alert('请选择spu!');
        }
        else
        {
            //根据所选spu获取产品信息
            $.ajax({
                url:"/orders-audit/get-attr-by-spu",
                type:'POST',
                dataType:'json',
                data:{
                    'spu':spu
                },
                success:function(data){
                    var number = $("#sku-list tr").length;
                    var key = number-1;
                    var color_str = '';
                    for(var i = 0; i < data['color'].length; i++) {
                        if (data['color'][i])
                        {
                            color_str += '<option value="'+data['color'][i]+'">'+data['color'][i]+'</option>';
                        }
                        else
                        {
                            color_str += '<option value="'+data['color'][i]+'">**</option>';
                        }
                    }
                    var size_str = '';
                    for(var i = 0; i < data['size'].length; i++) {
                        if (data['size'][i])
                        {
                            size_str += '<option value="'+data['size'][i]+'">'+data['size'][i]+'</option>';
                        }
                        else
                        {
                            size_str += '<option value="'+data['size'][i]+'">**</option>';
                        }
                    }
                    var sku_str = '';
                    for(var i = 0; i < data['sku'].length; i++) {
                        sku_str += '<option value="'+data['sku'][i]+'">'+data['sku'][i]+'</option>';
                    }
                    var content = '<tr>'+
                        '<td>'+number+'</td>'+
                        '<td><input type="text" name="title_'+key+'" value="'+data['title']+'" readonly>'+
                        '<input type="hidden" name="id_'+key+'" value="0"></td>'+
                        '<td><input type="text" name="spu_'+key+'" value="'+spu+'" readonly></td>'+
                        '<td><img class="zoom" name="image_'+key+'" src="http://admin.kingdomskymall.net.'+data['image']+'" width="50">'+
                        '<input type="hidden" name="image_'+key+'" value="'+data['image']+'"></td>'+
                        '<td><select name="color_'+key+'">'+color_str+'</select></td>'+
                        '<td><select name="size_'+key+'">'+size_str+'</select></td>'+
                        '<td><span class="submit">★</span></td>'+
                        '<td><select name="sku_'+key+'">'+sku_str+'</select></td>'+
                        '<td><input type="number" name="price_'+key+'" value="0"></td>'+
                        '<td><input type="number" name="qty_'+key+'" value="1"></td>'+
                        '</tr>';
                    $("#sku-list").append(content);
                }
            });
        }
    });

    //页面跳转到审核页
    $(".back").on('click',function () {
        window.location.assign('/orders-audit/index');
    });
</script>
