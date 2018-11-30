<?php 
use yii\helpers\Html;
use Yii;
$skuModel = new \app\models\ProductsVariant();
?>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>修改订单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link href="/assets/d9be4ce0/css/AdminLTE.min.css" rel="stylesheet"/> -->
    <style>
    table {background-color: transparent;border-spacing: 0;border-collapse: collapse;}
    .table-responsive {min-height: .01%;overflow-x: auto;}
    .box-body {border-top-left-radius: 0;border-top-right-radius: 0;border-bottom-right-radius: 3px;border-bottom-left-radius: 3px;padding: 10px;}
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {border: 1px solid #f4f4f4;}
    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {border-top: 1px solid #f4f4f4;}
    .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {border: 1px solid #ddd;}
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;text-align:center}
    .table {width: 100%;max-width: 100%;margin-bottom: 20px;}
    .table-responsive span{width:90px;position:relative;}
    .btn-success {color: #fff;background-color: #5cb85c;border-color: #4cae4c;}
    .btn {display: inline-block;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: normal;line-height: 1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;border: 1px solid transparent;border-radius: 4px;}
    .form-control {border-radius: 0;box-shadow: none;border-color: #d2d6de;}
    .form-control {width: 180px;height: 20px;padding: 2px 4px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);-webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;-o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;}
    </style>
</head>
<body>
<div style="background-color: #ecf0f5;">

<div class="box-footer">
    <span style="padding-left:8px;color:gray">提示：更换sku，先删除后添加sku</span>
</div>

<section class="content">
<form class="skuForm">
<div class="purchases-view box box-primary">
    <div class="box-body table-responsive">
        <table class="table table-bordered skuTable">
            <th>产品名称</th>
            <th>spu</th>
            <th>图片</th>
            <th>color</th>
            <th>size</th>
            <th>sku</th>
            <th>价格</th>
            <th>数量</th>
            <th>操作</th>
            <?php
            if($orderItems) :$i=0;foreach($orderItems as $key=>$list):
                $sku_info = $skuModel->find()->where(['sku' => $list['sku']])->one();
                ?>
                <tr class="<?php echo $list['sku'];?>">
                    <td><?= $list['title']?></td>
                    <td><?= $list['spu']?></td>
                    <td><img width="100" src="<?= $list['image']?>" </td>
                    <td><?= $list['color']?> </td>
                    <td><?= $list['size'] ?></td>
                    <td><?php echo $list['sku'];?></td>
                    <td ><span class='price'><?php echo $list['price'];?></span></td>
                    <td><?php echo $list['qty'];?></td>
                    <td><button type="button" class="delSku" onclick="delSku('<?php echo $list['sku'];?>')">删除</button></td>
                    <input type="hidden" name="postData[]" value='<?php echo json_encode($list) ?>'>
                </tr>
                <?php $i++;endforeach;endif;?>
                
        </table>
    </div>
</div>

    <div style="padding-left:8px;">
        <em style="color:#dd4b39 ">*</em> SKU：<input type="text" id="add_sku">
        <em style="color:#dd4b39 ">*</em> 数量：<input type="text" id="add_qty">
        <em style="color:#dd4b39 ">*</em> 价格：<input type="text" id="price">
        <button onclick="addSku()" type="button" class="btn btn-success addSku">增加SKU</button>
    </div>
    <div class="box-body table-responsive">
        <p>
            <span style="padding-right:38px"><em style="color:#dd4b39 ">*</em> 原因：</span>
            <select name="editReason" style="height:26px">
                <option value="">请选择替换发货原因</option>
                <option value="客户要求换货">1、客户要求换货</option>
                <option value="业务换货">2、业务换货</option>
            </select>
        </p>
            <input type="hidden" name="order_id" value="<?= $model->id ?>"/>
            <p><span style="padding-right:38px"><em style="color:#dd4b39 ">*</em> 备注：</span>
            <textarea name="notes" style="width:300px;height:60px"></textarea>
        </p>
    </div>
<form>
</section>
<div class="box-footer" style="padding-left:8px;">
    <?= Html::Button('提交', ['class' => 'btn btn-success btn-flat', 'onclick' => "submitSku()"]) ?>
</div>
</div>
<input type="hidden" name="order_status" value="<?= $model->status ?>"/>
</body>
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/js/layer/layer.js"></script>
<script>
    var isSave = false;
    //删除sku jieson 2018.11.09
    function delSku(sku)
    {
        layer.confirm("确定删除吗？", function(yes){
            isSave = true;
            // 删除操作
            $("."+sku).css('background','#dd4b39');
            $("."+sku).children().last().attr('disabled', true);
            var undo = '<button type="button" class="undoSku" onclick="undoSku(\''+ sku +'\')">撤销</button>';
            $("."+sku).children().eq(8).empty().append(undo);
            layer.closeAll('dialog');
        });
    }

    // 撤销删除
    function undoSku(sku)
    {
        layer.confirm("确定撤销吗？", function(yes){
            // isSave = false;
            $("."+sku).css('background','');
            sku1 = "'"+ sku +"'";
            $isAddsku = sessionStorage.getItem(sku1);
            if($isAddsku){
                $("."+sku).css('background','#5cb85c');
            }

            $("."+sku).children().last().attr('disabled', false);
            var undo = '<button type="button" class="delSku" onclick="delSku(\''+ sku +'\')">删除</button>';
            $("."+sku).children().eq(8).empty().append(undo);
            layer.closeAll('dialog');
        });
    }

    //添加SKU
    function addSku() {
        if($("#add_sku").val() && $("#add_qty").val() && $('#price').val())
        {
            // 判断sku是否重复
            var isRepeat = false;
            // $(".skuTable tbody tr").each(function(i){
            //     if ($("#add_sku").val() == $(this).children().eq(5).text()) {
            //         // isRepeat = true;
            //         //layer.msg("已存在重复的sku！", {icon:0});return false;
            //     }
            // });
            if (!isRepeat) {
                $.get("/replace/add-sku", {sku:$("#add_sku").val(), qty:$("#add_qty").val(),price:$('#price').val(), id:<?=$model->id?>}, function (data) {
                    if (data == 0) {
                        layer.msg("没有查询到该sku的相关信息！");
                    } else {
                        isSave = true;
                        var res = eval("("+ data +")");
                        var tr = "<tr class='"+ res.sku +"' style='background:#5cb85c'>";
                        tr+= "<td>"+ res.title +"</td>";
                        tr+= "<td>"+ res.spu +"</td>";
                        tr+= "<td><img width='100' src='"+ res.image +"'</td>";
                        tr+= "<td>"+ res.color +"</td>";
                        tr+= "<td>"+ res.size +"</td>";
                        tr+= "<td>"+ res.sku +"</td>";
                        tr+= "<td>"+ res.price +"</td>";
                        tr+= "<td>"+ res.qty +"</td>";
                        tr+= "<td><button type='button' onclick=\"delSku('"+ res.sku +"')\">删除</button></td>";
                        tr+= "<input type='hidden' name='postData[]' value='"+JSON.stringify(res)+"'/>";
                        tr+= "</tr>";
                        
                        // 记录添加的sku
                        sku = "'"+ res.sku +"'";
                        sessionStorage.setItem(sku,sku);
            
                        $(".skuTable").append(tr);
                    }
                    
                });
            }
            
        }else{
            layer.msg("SKU、数量、价格、备注不能为空");
        } 
    }

    // 提交sku
    function submitSku()
    {
        if (!isSave) {layer.msg('你没做任何操作，无需提交',{icon:0});return false;}
        if ($(".skuTable tbody").children().length == 1) {layer.msg('至少有一个sku产品信息！',{icon:0});return false;}
        var editReason = $("select[name=editReason]").children("option:selected").val();
        if (editReason == '') {layer.msg('请选择换货原因！',{icon:0});return false;}
        if ($("textarea[name=notes]").val() == '') {layer.msg('备注不能为空',{icon:0});return false;}

        layer.confirm("提交前请确认你修改的信息! 提交成功后,订单状态会变为-已确认", function(yes){
            $.get("/replace/save-sku", $("form").serializeArray(), function(res){
                if (res == 1) {
                    layer.msg("保存成功！", {icon:1}, function() {
                        parent.layer.closeAll();
                        window.parent.location.reload();
                    });
                } else if (res == -1) {
                    layer.msg("修改失败！该状态下的订单不能修改！，请关闭返回", {icon:0});
                } else {
                    layer.msg("保存失败！请刷新重试", {icon:0});
                }
            });
        });
    }

    // 用户引导
    function userGuide()
    {
        var ip = "<?= $_SERVER['REMOTE_ADDR']; ?>";
        var oldIp = localStorage.getItem("<?= Yii::$app->user->id ?>");
        if (ip != oldIp) {
            localStorage.setItem("<?= Yii::$app->user->id ?>", ip);
            layer.tips('第一步，先点击删除', '.delSku', {
                tips: [4, '#f0ad4e'], //还可配置颜色#5CB85C,#f0ad4e,#3C8DBC
                tipsMore: true,
                end: function() {
                    layer.tips('第二步，添加SKU', '.addSku', {
                        tips: [1, '#f0ad4e'], //还可配置颜色#5CB85C,#f0ad4e,#3C8DBC
                        tipsMore: true,
                        end: function() {
                            layer.tips('第三步，点击提交', '.btn-flat', {
                                tips: [2, '#f0ad4e'], //还可配置颜色#5CB85C,#f0ad4e,#3C8DBC
                                tipsMore: true
                            });
                        }
                    });
                }
            });
        }
        
    }
    userGuide();
</script>