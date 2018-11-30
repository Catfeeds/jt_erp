<?php
/* @var $this yii\web\View */
/* @var $model app\models\Orders */
/* @var $order_info app\models\Orders */
/* @var $data_arr app\models\Orders */

$this->title = $order_info->name;
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
                        <td><input type="text" value="<?= $order_info['id'];?>"></td>
                        <th>订单状态</th>
                        <td><input type="text" value="<?= \app\models\Orders::$status_arr[$order_info['status']]?>"></td>
                    </tr>
                    <tr>
                        <th>站点</th><td><input type="text" name="" value="<?= $order_info['website_id'];?>"></td>
                        <th>结算状态</th><td><input type="text" name="" value="<?= \app\models\Orders::$money_status_arr[$order_info['money_status']];?>"></td>
                    </tr>
                    <tr>
                        <th>姓名</th>
                        <td><input type="text" name="" value="<?= $order_info['name'];?>"></td>
                        <th>电话</th>
                        <td><input type="text" name="" value="<?= $order_info['mobile']?>"></td>
                    </tr>
                    <tr>
                        <th>邮箱</th>
                        <td><input type="text" name="" value="<?= $order_info['email']?>"></td>
                        <th>国家</th><td><input type="text" name="" value="<?= $order_info['country'];?>"></td>
                    </tr>
                    <tr>
                        <th>省份</th><td><input type="text" name="" value="<?= $order_info['district'];?>"></td>
                        <th>城市</th><td><input type="text" name="" value="<?= $order_info['city'];?>"></td>
                    </tr>
                    <tr>
                        <th>区域</th><td><input type="text" name="" value="<?= $order_info['area'];?>"></td>
                        <th>地址</th><td><input type="text" name="" value="<?= $order_info['address']?>">
                    </tr>
                    <tr>
                        <th>购买数量</th><td><input type="text" name="" value="<?= $order_info['qty'];?>">
                        <th>总价</th><td><input type="text" name="" value="<?= $order_info['total']?>">
                    </tr>
                    <tr>
                        <th>邮编</th><td><input type="text" name="" value="<?= $order_info['post_code'];?>"></td>
                        <th>操作人备注</th><td><textarea name="" id="" cols="30" rows="10" style="resize: none;width: 320px;height: 72px;" disabled><?= $order_info['comment_u'];?></textarea></td>
                    </tr>
                    <tr><th>留言</th><td><textarea name="" id="" cols="30" rows="10" style="resize: none;width: 320px;height: 72px;" disabled><?= $order_info['comment'];?></textarea></td>
                        <th>下单时间</th><td><input type="text" value="<?= $order_info['create_date'];?>"></td>
                    </tr>
                </table>
            </div>
        </div>
        <ul class="nav nav-tabs Two">
            <h3>订单详情信息</h3>
        </ul>
        <div id="tableLayer">
            <fieldset>
                <table id="sku-list" class="table table-hover table-bordered table-list order-table">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>产品名称</th>
                        <th>spu</th>
                        <th>图片</th>
                        <th>color</th>
                        <th>size</th>
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
                            <td>
                                <select name="sku_<?= $key;?>">
                                    <option value="<?php echo $value['sku']?>"><?php echo $value['sku']?$value['sku']:'**'?></option>
                                </select>
                            </td>
                            <td><input type="number" name="price_<?= $key;?>" value="<?= $value['price']?>"></td>
                            <td><input type="number" name="qty_<?= $key;?>" value="<?= $value['qty']?>"></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </fieldset>
        </div>
        <ul class="nav nav-tabs Three">
            <div id="show_record"><h3>订单操作记录</h3></div>
        </ul>
        <div id="order_record">
            <div class="span3">
                <table class="table table-bordered" width="510" border="2" align="center" cellpadding="0" cellspacing="0">
                    <tbody><tr  style="background:#f5f5f5;font-size:16px;font-weight:bold;">
                        <th>序号</th><th>状态</th><th>用户</th><th>操作类型</th><th>详细</th><th>时间</th>
                    </tr></tbody>
                    <?php foreach ($order_record_arr as $k => $info):?>
                        <tr>
                            <td><?= $k+1;?></td>
                            <td><?= \app\models\Orders::$status_arr[$info['id_order_status']];?></td>
                            <td><?= $info['user_name'];?></td>
                            <td><?= \app\models\OrderRecord::$type_arr[$info['type']];?></td>
                            <td><?= $info['desc'];?></td>
                            <td><?= $info['created_at'];?></td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    $('input').attr("readonly","readonly");
    $('select').attr("disabled","disabled");
    //页面跳转到审核页
    $(".back").on('click',function () {
        window.location.assign('/orders/index');
    });
</script>
