<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="applicable-device" content="mobile">
    <title>上传SKU</title>
    <link rel="stylesheet" href="/css/global.css">
    <script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>

</head>
<body>
<div class="header">
    <h3>上传SKU</h3>
    <a href="/location-stock/select-code">返回</a>
</div>
<div class="main">
    <a class="back" href="/location-stock/select-code">更换库位</a>
    <ul>
        <li>
            <div class="label">库位：</div>
            <div id="libraryNumber" class="text left-style"></div>
        </li>
<!--        <li>-->
<!--            <div class="label">收货单号：</div>-->
<!--            <div class="text"><input id="receiptSn" class="ipt" type="text"></div>-->
<!--        </li>-->
        <li>
            <div class="label">SKU：</div>
            <div class="text"><input id="ipt" class="ipt" type="text"></div>
        </li>
        <li style="display: none;">
            <div class="label">总数量：</div>
            <div id="totalNum" class="text left-style">0</div>
        </li>
        <li>
            <div class="label"></div>
            <div id="btn" class="btn"><button class="btn">上传数据</button></div>
        </li>
    </ul>
    <div id="write-box">
    </div>
</div>
<input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
<script>
    var csrfToken = $('#_csrf').val();
    // 初始化页面时，获取本地存数数据库位名称
    // 如果不存在，就调回录入库位页面
    var libraryNumberText = localStorage.getItem('libraryNumber');
    if (!libraryNumberText){
        location.href = 'index.html';
    }
    var libraryNumber = getId('libraryNumber');
    var ipt = getId('ipt');
    var btn = getId('btn');
    var totalNumEle = getId('totalNum');
    var writeBox = getId('write-box');
    // 本地存储对象
    var localStorageObj = {};
    // 提交对象
    var submitObj = {
        location_code: libraryNumberText,
        sku_list: [],
        '_csrf':csrfToken,
    };
    // 控制在请求时，反复点击按钮
    var submitContr = true;
    libraryNumber.innerHTML = libraryNumberText;
    // 初始化页面时，获取本地存储的 SKU、总数量的对象数据
    // 如果不存在，就初始化一个 dataObj
    // 如果存在，就直接赋值给 dataObj，要转换下 json
    var getObj = localStorage.getItem('dataObj');
    if (!getObj){
        var dataObj = {
            name: libraryNumberText,
            skuNum: []
        };
    } else {
        var dataObj = JSON.parse(getObj);
        createEle(dataObj.skuNum);
    }
    // 监听 SKU 的输入框是否输入按下了回车键
    ipt.onkeydown = function (e) {
        if (e.keyCode == '13' ){
            if (ipt.value == '' ){
                alert('请输入正确地SKU条形码');
                //return false;
            } else {
                var skuNumArray = dataObj.skuNum;
                var totalNum = parseInt(totalNumEle.innerHTML);
                var recordsNum = 0;
                // 判断是否没有数据，也就是第一次添加
                if (skuNumArray.length <= 0) {
                    dataObj.skuNum.push({
                        number: ipt.value,
                        totalNum: 1
                    });
                } else {
                    // 判断是否有重复的；
                    // 如果没有，就让 recordsNum 跟随添加来监听，此处循环，不能直接 push；
                    // 如果有，就让总数加1
                    for (var i = 0; i < skuNumArray.length; i++){
                        if (skuNumArray[i].number != ipt.value){
                            recordsNum++;
                        } else {
                            totalNum = skuNumArray[i].totalNum + 1;
                            skuNumArray[i].totalNum = totalNum;
                        }
                    }
                }
                // 如果没有重复的就添加新得
                if (skuNumArray.length - recordsNum == 0){
                    totalNum = 1;
                    dataObj.skuNum.push({
                        number: ipt.value,
                        totalNum: totalNum
                    });
                }
                // 赋值、保存到本地存储，对象要转换成 json 格式
                totalNumEle.innerHTML = totalNum;
                writeBox.innerHTML = '';
                localStorageObj = JSON.stringify(dataObj);
                localStorage.setItem('dataObj', localStorageObj);
                ipt.value = '';
                totalNumEle.innerHTML = 0;
                createEle(dataObj.skuNum);
            }
        }
    }
    // 提交数据
    btn.onclick = function () {
//        var receiptSn = $('#receiptSn').val();
//        if(!receiptSn) {
//            alert('请输入收货单号');
//            return false;
//        }
//        submitObj.receipt_sn = receiptSn
        if (dataObj.skuNum.length <= 0) {
            alert('没有要提交的数据');
        } else if (submitContr) {
            submitContr = false;
            btn.innerHTML = '正在提交中...';
            btn.style.backgroundColor = 'grey';
            var _divs = writeBox.childNodes;
            for (var i = 0; i < _divs.length; i++) {
                if (_divs[i].className == 'write') {
                    var _p = _divs[i].getElementsByTagName('p')[0];
                    var _b = _divs[i].getElementsByTagName('div')[0].getElementsByTagName('b')[0];
                    submitObj.sku_list.push({
                        sku: _p.innerHTML.replace(/<[^>]+>/g,""),
                        qty: _b.innerHTML.replace(/<[^>]+>/g,""),
                    })
                }
            }
            // 提交数据，成功就刷新页面，并清除本地存储里地对象 dataObj
            $.ajax({
                url: '/location-stock/ajax-add-stock',
                type: 'post',
                data: submitObj,
                dataType: 'json',
                success: function (res) {
                    if (res.status == '200') {
                        localStorage.removeItem('dataObj');
                      alert(res.msg);
                        location.reload();
                    } else {
                        alert(res.msg);
                    }
                }
            });
        }
    };
    // id 获取元素
    function getId (idStr) {
        return document.getElementById(idStr);
    }
    // 生成数据列表
    function createEle (arrayObj) {
        for (var j = 0; j < arrayObj.length; j++){
            var div = document.createElement('div');
            div.className = 'write';
            div.innerHTML = '<p>' + arrayObj[j].number + '</p>' +
                '<div class="num" onclick="setNum(this)"> \
                    <b>' + arrayObj[j].totalNum + '</b>\
									<input type="text">\
									<input type="hidden" value="' + arrayObj[j].totalNum + '">\
									<em class="iconfont icon-qianbi"></em>\
								</div>';
            writeBox.appendChild(div);
        }
    }
    // 编辑数量
    function setNum (e) {
        var _p = $(e).parent().find('p');
        var _b = e.getElementsByTagName('b')[0];
        var _input = e.getElementsByTagName('input')[0];
        var _inputHidden = e.getElementsByTagName('input')[1];
        var _span = e.getElementsByTagName('span')[0];
        var _bText = parseInt(_b.innerHTML);
        _inputHidden = parseInt(_inputHidden.value);
        _input.style.display = 'block';
        _input.focus();
        _input.value = _bText;
        _input.onblur = function () {
            var _inputVal = parseInt(_input.value);
            // if (_inputVal > _inputHidden){
            // 	_input.value = _inputHidden;
            // 	_b.innerHTML = _inputHidden;
            // } else if (_inputVal <= 0) {
            // 	_b.innerHTML = 1;
            // } else {
            // 	_b.innerHTML = _inputVal;
            // }
          if (_inputVal <= 0) {
            _inputVal = 1;
          }
          _b.innerHTML = _inputVal;
          var getObj = localStorage.getItem('dataObj');
          var dataObj = JSON.parse(getObj);
          var skuNum = [];
          skuNum = dataObj.skuNum;
          for(var i in skuNum) {
            if(skuNum[i].number == _p.html()) {
              skuNum[i].totalNum = _inputVal;
            }
          }
          dataObj.skuNum = skuNum;
          localStorageObj = JSON.stringify(dataObj);
          localStorage.setItem('dataObj', localStorageObj);
            _input.style.display = 'none';
        }
    }
</script>
</body>
</html>