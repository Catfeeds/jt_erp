<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="applicable-device" content="mobile">
    <title>入库</title>
    <link rel="stylesheet" href="/css/global.css">
    <style>

    </style>
    <script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
</head>
<body>
<div class="header">
    <h3>入库</h3>
    <a href="/">返回</a>
</div>
<div class="main">
    <div class="title">请扫描库位</div>
    <div><input id="libraryNumber" class="ipt" type="text" placeholder="请输入库位号">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

    </div>
    <button id="btn" class="btn">扫描SKU</button>
</div>
<script>
    // var glob = {};
    // (function(_self){
    // 	var aa = {
    // 		onload : function(){
    // 			var winWidth = document.documentElement.clientWidth;
    // 			document.children[0].style.fontSize = winWidth / 10 + 'px';
    // 		}
    // 	}
    // 	_self.aa = aa
    // })(glob)
    // glob.aa.onload()
    // window.onresize = glob.aa.onload
    var libraryNumber = document.getElementById('libraryNumber');
    var btn = document.getElementById('btn');
    localStorage.clear();
    btn.onclick = function () {
        var valueText = libraryNumber.value;
        if (valueText == ''){
            alert('请输入库位号');
            libraryNumber.focus();
        } else {
            // 提交数据库，如果有入库位号，就保存到本地存储并跳转页面
            localStorage.setItem('libraryNumber', valueText);
            var csrfToken = $('#_csrf').val();
            $.ajax({
                url: '/location-stock/ajax-select-code',
                type: 'post',
                data: {
                    code: valueText,
                    '_csrf':csrfToken
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status == '200'){
                        location.href = '/location-stock/add-stock';
                    } else {
                        alert(res.msg);
                    }
                }
            })
        }
    };
</script>
</body>
</html>
