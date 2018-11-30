<?php
/* @var $this yii\web\View */

$this->title = "订单操作记录";
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
</style>
<div class="orders-item-form box box-primary">
    <div class="box-body table-responsive">
        <div class="form-actions">
            <button type="submit" class="btn btn-primary back" style="margin-bottom: 10px;margin-right: 10px;margin-top: 10px;">返回</button>
        </div>
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
    //页面跳转到审核页
    $(".back").on('click',function () {
        window.location.assign('/order-status-change/index');
    });
</script>
