<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title><?=$website->title;?></title>
	<link rel="stylesheet" href="/themes/<?=$website->theme;?>/css/sm.css">
    <link rel="stylesheet" href="/themes/<?=$website->theme;?>/css/sm-extend.css">
    <link rel="stylesheet" href="/themes/<?=$website->theme;?>/css/shop.css">
    <?php
    if(empty($website->facebook)):
    ?>
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1860040604046442');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=1860040604046442&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->
    <?php endif;?>
    <?php
    echo $website->facebook;
    echo $website->google;
    echo $website->other;
    ?>
	<script>
        fbq('track', 'ViewContent');
        var requestId = "<?php echo($requestId); ?>";
		var currency = "<?=$website->currency[$website->sale_city];?>";
	</script>
    <style type="text/css">
        .detatwo-title{font-weight: bold;}
    </style>
</head>
<body>
    <div class="page-group detatwo">
        <div id="home" class="page page-current">
            <div class="content">
                <div class="detatwo-wrap">
                    <div class="detatwo-banner">
                        <div class="swiper-container" data-space-between='10'>
                            <div id="swiperWrapper" class="swiper-wrapper">
                            </div>
                            <div class="page-num"><span>1</span> / <span></span></div>
                        </div>
                    </div>
                    <!-- datatwo-price S -->
                    <div class="detatwo-price">
                        <div class="price-context clearfix">

                        </div>
                        <div class="detatwo-day">
                            <span class="label">Ninja van</span>
                            <span class="time-wrap">Ends in
                                <span class="timer">
                                    <span id="hour"></span>
                                    :
                                    <span id="min"></span>
                                    :
                                    <span id="sec"></span>
                                </span>
                            </span>
                        </div>
                        <div class="detatwo-number">

                        </div>
                        <div class="detatwo-profile">
                            <p class="detatwo-sale-info"></p>
                            <p class="detatwo-title"></p>
                        </div>
                    </div>
                    <!-- datatwo-price E -->
                    <div class="detatwo-bars">
                        <li>商品描述</li>
                        <li>商品参数</li>
                        <li>最新订单</li>
                    </div>
                    <!-- product-info S -->
                    <div class="detatwo-product-info">
                        <div class="m-img detatwo-context">
                            <div id="info"></div>
                        </div>
                        <div class="detatwo-params detatwo-context">
                            <div id="attr_info"></div>
                        </div>
                    </div>
                    <!-- product-info E -->
                    <!-- page-order S
                    <div class="detatwo-page-order detatwo-context">
                        <div class="order-title">
                            <h1>Orders</h1>
                        </div>
                    </div>
                     -->
                    <!-- page-order E
                    <div class="detatwo-recommend-products">
                        <h3>Title…</h3>
                        <dl class="clearfix">
                            <dt>
                                <a href="###"><img src="/themes/<?=$website->theme;?>/images/detatwo_pic1.jpg"></a>
                            </dt>
                            <dd>
                                <h5>2018 title</h5>
                                <div class="price">฿1480</div>
                                <a class="buy-now" href="###">Buy</a>
                            </dd>
                        </dl>
                    </div>
                    -->

                    <!-- 评论 S -->
                    <div class="details-reviews">
                        <div class="details-bwtitle">评论</div>
                        <div class="list-block list-li-marquee1">
                            <ul>
                                <?php foreach ($comment as $comment_list): ?>
                                    <li>
                                        <p><?=$comment_list['body'];?></p>
                                        <div class="information clearfix">
                                            <span class="fl"><?=$comment_list['name'];?>（<?=$comment_list['phone'];?>）</span>
                                            <span class="fr"><?=$comment_list['add_time'];?></span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <!-- 评论 E -->
                    <div class="details-footer">
                        <img src="/themes/<?=$website->theme;?>/images/details_footer_icon1.png">
                        <img src="/themes/<?=$website->theme;?>/images/details_footer_icon2.png">
                        <img src="/themes/<?=$website->theme;?>/images/details_footer_icon3.png">
                        <img src="/themes/<?=$website->theme;?>/images/details_footer_icon4.png">
                        <img src="/themes/<?=$website->theme;?>/images/details_footer_icon5.png">
                        <img src="/themes/<?=$website->theme;?>/images/details_footer_icon6.png">
                    </div>
                </div>
            </div>
            <nav class="bar bar-tab details-nav">
                <!--a class="tab-item" target="_blank" href="https://put.zoosnet.net/LR/Chatpre.aspx?id=PUT13291804&lng=en" external><span></span><span>Live Chat</span></a-->
                <a class="tab-item" href="#cart-attr"><span></span><span>Buy Now</span></a>
            </nav>
        </div>

        <!--Cart1 begin-->
        <div class="page" id="cart-attr">
            <header class="bar bar-nav" style="background-color: #000;">
                <a class="icon icon-left pull-left" href="#home"></a>
                <h1 class="title">确认订单信息</h1>
            </header>
            <div class="content">
                <form class="details-form" action="javascript:;">
                    <div class="main-bg">
                        <div class="main">
                            <?php
                            if($website->is_group):
                            ?>
                            <div class="main-box">
                                <div class="label">产品</div>
                                <div class="operation-box" id="group_box">
                                    <?php
                                        $websiteGroups = \app\models\WebsitesGroup::findAll(['website_id' => $website->id]);
                                        $i = 0 ;
                                        foreach($websiteGroups as $group_v):
                                            ?>
                                            <span <?php echo $i==0?'class="active"':'';?> data-group="<?=$group_v->id?>"><?=$group_v->group_title?></span>
                                            <?php
                                            $i++;
                                        endforeach;
                                    ?>
                                </div>
                            </div>
                                <div id="group_options">

                                </div>
                            <?php else:?>

                                <div id="sizeBox" class="main-box sizeBox">
                                    <div class="label">尺码</div>
                                    <div class="operation-box"></div>
                                </div>
                                <div id="colorBox" class="main-box colorBox">
                                    <div class="label">颜色</div>
                                    <div class="operation-box"></div>
                                </div>

                                <div class="details-second-price">
                                    <div id="secondSizeBox" class="main-box">
                                        <div class="label">尺码</div>
                                        <div class="operation-box"></div>
                                    </div>
                                    <div id="secondColorBox" class="main-box">
                                        <div class="label">颜色</div>
                                        <div class="operation-box"></div>
                                    </div>
                                </div>
                                <div class="details-second-price-btn"></div>
                            <?php endif;?>


                            <div class="main-box">
                                <div class="label">合计</div>
                                <div class="number-box">
                                    <b class="fl">-</b>
                                    <input class="num" type="text" name="number" readonly="readonly" value="1">
                                    <b class="fl">+</b>
                                    <strong id="totalPrice"><!-- <em class="rmb">₱</em><em>2300.00</em> --></strong>
                                </div>
                            </div>
                        </div>
                        <div class="submit-btn">
                            <a href="#cart-form">下一步</a>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <!--Cart1 end-->
        <!--Cart2 begin-->
        <div class="page" id="cart-form">
            <header class="bar bar-nav" style="background-color: #000;">
                <a class="icon icon-left pull-left" href="#cart-attr"></a>
                <h1 class="title">确认订单信息</h1>
            </header>
            <div class="content">
                <form class="details-form" action="javascript:;">
                    <div id="inputBox" class="main-bg">
                        <div class="main">
                            <div class="main-box">
                                <div class="label">* 姓名</div>
                                <div class="operation-box">
                                    <input type="text" name="inputName" placeholder="请输入您的姓名">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* 电话</div>
                                <div class="operation-box">
                                    <input type="text" name="mobile" placeholder="（+63 09 XXXXXXXXX）">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">电子邮箱</div>
                                <div class="operation-box">
                                    <input type="text" name="email" placeholder="请输入您的电子邮箱">
                                </div>
                            </div>
                            <div class="main">
                            <div class="main-box">
                                <div class="label">* 省</div>
                                <div class="operation-box">
                                    <?php

                                    $provinces = \app\models\ShipingArea::findAll(['country' => $website->sale_city]);
                                    if($provinces):
                                        $options = [];
                                        foreach($provinces as $p)
                                        {
                                            $options[$p->province] = $p->province;
                                        }
                                        echo \yii\helpers\Html::dropDownList('province', '', ['' => '省'] + $options, ['onchange'=>'getCity("'.$website->sale_city.'", this)', 'id' => 'province']);
                                    else:
                                    ?>
                                    <input type="text" name="province" id="province" placeholder="provinsi">
                                    <?php
                                    endif;
                                    ?>
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* 城市</div>
                                <div class="operation-box">
                                    <select name="city" onchange="getArea('<?=$website->sale_city?>', this)" id="city_select">
                                        <option value="">城市</option>
                                    </select>
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* 地区</div>
                                <div class="operation-box">
                                    <select onchange="setPostCode(this)" id="area_select">
                                        <option value="">地区</option>
                                    </select>
                                    <input type="hidden" id="area_val" name="area" >
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* 地址</div>
                                <div class="operation-box">
                                    <input type="text" name="address" placeholder="Suggest you fill in the company address">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">*邮编</div>
                                <div class="operation-box">
                                    <input type="text" id="postCode" name="postCode">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">备注</div>
                                <div class="operation-box">
                                    <textarea name="comment" placeholder="备注"></textarea>
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">如何支付</div>
                                <div class="operation-box activity">
                                    <div class="activity-title">
                                        <em></em>货到付款
                                    </div>
                                    <p>重要注意事项：在付款前检查</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-btn">
                        <input class="button" type="submit" value="确认">
                    </div>
                </form>
            </div>

        </div>
        <!--Cart2 end-->
        <!--popup-->
        <div class="popup popup-success">
            <div class="content-block">
                <h1>购买成功</h1>
                <div class="card" style="min-height: 2rem;padding: 10px;">
                    <div class="card-content" style="padding:0 25px;">
                        <p style="text-align:center"><img width="100px" src="/images/th_success-icon.png"></p>
                        <p style="color:#339966;font-size: 1rem;text-align:center;">购买成功</p>
                        <p style="color:#ff7300;font-size: 0.8rem;text-align:center">
                            订单号: <span id="order_id"></span><br>
                            你成功地抢购了. <br>
                            请确认您的电话已接通，我们会尽快将您的货物运送到您。
                        </p>
                        <p style="color: red;">
                            注：在我们的网站上，付款方式只是COD（货到付款），我们不要求客户将钱转账给我们，也不会因为其他原因支付其他付款方式。
                        </p>
                        联系我们:<br>
                        <a class="external" href="mailto:supportth@kingdomskymall.com" style="text-decoration:none;outline:0;color:#339966;">supportth@kingdomskymall.com</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- icon-footer-popup -->
    <div class="popup popup-footer-text">
        <div class="content-block">
            <p></p>
        </div>
    </div>
	<script type='text/javascript' src='/themes/<?=$website->theme;?>/js/zepto.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='/themes/<?=$website->theme;?>/js/sm.js' charset='utf-8'></script>
    <script type='text/javascript' src='/themes/<?=$website->theme;?>/js/sm-extend.js' charset='utf-8'></script>
    <script type="text/javascript">
    	var end_timestamp_num = <?=$website->sale_end_hours?>;
    </script>
    <script src="/themes/<?=$website->theme;?>/js/shop_details2.js"></script>
</body>
<!-- Start of kingdomskymall Zendesk Widget script -->
<script>/*<![CDATA[*/window.zE||(function(e,t,s){var n=window.zE=window.zEmbed=function(){n._.push(arguments)}, a=n.s=e.createElement(t),r=e.getElementsByTagName(t)[0];n.set=function(e){ n.set._.push(e)},n._=[],n.set._=[],a.async=true,a.setAttribute("charset","utf-8"), a.src="https://static.zdassets.com/ekr/asset_composer.js?key="+s, n.t=+new Date,a.type="text/javascript",r.parentNode.insertBefore(a,r)})(document,"script","d77a113e-8676-48cc-bf2a-4e9d8c39a01f");/*]]>*/</script>
<!-- End of kingdomskymall Zendesk Widget script -->
</html>