<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>收货反馈</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>        
        *{margin:0;padding:0;list-style:none;}
        body{font-size:12px;overflow:auto}
        .main{width:260px;min-height:180px;height:auto;position:fixed;bottom:60px;border:1px solid #ccc;background-color: white;}
        *html .main{position:absolute;top:expression(eval(document.documentElement.scrollTop));margin-top:320px;}
        .main2{width:240px;height:auto;position:relative;padding:5px;}
        .main2 ul li{width:100%;height:40px;text-align:left;}
        .bar{width:25px;height:105px;position:absolute;right:240px;top:-1px;background:url(/images/mini_bg.png) no-repeat;display:block;}
    </style>
</head>
<body>
<div class="main">
    <div class="main2">
        <a href="javascript:" class="bar">
            <?php if (!empty($data)) { ?>
                <span class="label label-warning" style="position: absolute;top: -14px;right: 10px;"><?= count($data) ?></span>
            <?php } ?>
        </a>
        <ul>
            <?php 
                foreach ($data as $v) {
            ?>
                <li><?= $v ?></li>
            <?php } ?>
            <?php if (empty($data)) {?>
                <li>暂时还没有收货反馈！</li>    
            <?php }?>
        </ul>
    </div>
</div>
<script src="/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
$(function(){
    $('.main').css('right','0px');
    $('.main').animate({right:'-262'},500);
    $('.bar').css('background-position','-27px 0px');
	var expanded = true;
	$('.bar').click(function(){
		if(expanded){
            $('.main').animate({right:'0px'},500);
            $('.bar').css('background-position','-0px 0px');
		}else{
            $('.main').animate({right:'-262'},500);
            $('.bar').css('background-position','-27px 0px');
		}
		expanded = !expanded;
	});

});
</script>
</body>
</html>