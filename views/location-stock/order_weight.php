<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="applicable-device" content="mobile">
    <title>称重</title>
    <link rel="stylesheet" href="/css/global.css">
    <script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
</head>
<body>
<div class="header">
    <h3>重量</h3>
    <a href="/">返回</a>
</div>
<div class="main">
    <div><input id="weightNumber" class="ipt" type="text" placeholder="请输入重量"></div>
    <div><input id="orderNumber" class="ipt" type="text" placeholder="请输入订单,按回车键 Enter 提交"></div>
</div>
<input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/js/layer/layer.js"></script>
<script>
    window.onload = function () {
        var csrfToken = $('#_csrf').val();
        var weightNumber = document.getElementById('weightNumber');
        var orderNumber = document.getElementById('orderNumber');
        // 进入页面，重量输入框获取焦点
        weightNumber.focus();
        weightNumber.onkeydown = function (e) {
            if (e.keyCode == 13){
                if (weightNumber.value !== '') {
                    orderNumber.focus();
                }
            }
        };
        orderNumber.onkeydown = function (e) {
            if (e.keyCode == 13){
                if (weightNumber.value == '') {
                    layer.msg('请输入重量');
                    weightNumber.focus();
                } else if (orderNumber.value == ''){
                    layer.msg('请输入订单');
                    orderNumber.focus();
                } else {
                    var wightObj = {
                        orders: orderNumber.value,
                        weight: weightNumber.value,
                        '_csrf':csrfToken
                    };
                    // 提交数据
                    $.ajax({
                        url: '/location-stock/ajax-order-weight',
                        type: 'post',
                        data: wightObj,
                        dataType: 'json',
                        success: function (res) {
                            if (res.status == '200') {
                                layer.msg(res.msg, {icon:1,time:2000}, function(){
                                    if(res.pdf){
                                        printJS(res.pdf);
                                    }
                                    location.reload();
                                });
                                
                            } else {
                                layer.msg(res.msg, {icon:0});
                            }
                        }
                    });
                }
            }
        }
    }
</script>
</body>
</html>
