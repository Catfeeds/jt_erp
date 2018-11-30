<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="applicable-device" content="mobile">
    <title>下架SKU</title>
    <link rel="stylesheet" href="/css/up_sku_stock.css">
    <style>

    </style>
    <script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
</head>
<body>
<div class="header">
    <h3>下架SKU</h3>
    <a href="###">返回</a>
</div>
<div class="main">
    <div class="title">下架SKU</div>
    <div>
        <input id="orderNumber" class="ipt" type="text" placeholder="请输入单号">
        <input id="libraryNumber" class="ipt" type="text" placeholder="请输入库位号">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
    </div>
    <button id="btn" class="btn">确认</button>
</div>
<script>
  var orderNumber = document.getElementById('orderNumber');
  var libraryNumber = document.getElementById('libraryNumber');
  var btn = document.getElementById('btn');
  localStorage.clear();
  orderNumber.onkeydown = function (e) {
    if (e.keyCode == 13) {
      if (this.value !== '') {
        libraryNumber.focus();
      }
    }
  };
  btn.onclick = function () {
    var orderText = orderNumber.value;
    var libraryText = libraryNumber.value;
    if (orderText == ''){
      alert('请输入单号');
      orderNumber.focus();
    } else if (libraryText == '') {
      alert('请输入正确的库位号');
      libraryNumber.focus();
    } else {
      // 提交数据库，如果有入库位号，就保存到本地存储并跳转页面'
      localStorage.setItem('orderNumber', orderText);
      localStorage.setItem('libraryNumber', libraryText);
      var csrfToken = $('#_csrf').val();
      $.ajax({
        url: '/location-stock/ajax-select-order-sku',
        type: 'post',
        data: {
          number: orderText,
          code: libraryText,
          '_csrf':csrfToken
        },
        dataType: 'json',
        success: function (res) {
          if (res.status == '200'){
            localStorage.setItem('orderType', res.data.type);
            location.href = '/location-stock/up-sku-stock-info';
          } else {
            alert(res.msg);
          }
        }
      });
    }
  };
</script>
</body>
</html>
