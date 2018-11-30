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
                            <span class="label">黑貓宅急便配送</span>
                            <span class="time-wrap">限時下殺
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
                        <li>商品說明</li>
                        <li>商品規格</li>
                        <li>最新評論</li>
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
                    <div class="details-reviews detatwo-context">
                        <div class="details-bwtitle">評論</div>
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
                    <!-- 用户须知 S -->
                    <div class="details-knowledge">
                        <div class="details-bwtitle">用戶須知</div>
                        <div class="text-list">
                            <ul>
                                <li>
                                    <h3>關於發貨方式</h3>
                                    <p>默認使用臺灣黑貓宅急便配送，貨到付款</p>
                                </li>
                                <li>
                                    <h3>關於配送時間</h3>
                                    <p>下單成功之後，我們會按照下單先後順序進行配貨，配貨周期為3個工作日左右，一般到達時間為7個工作日左右</p>
                                </li>
                                <li>
                                    <h3>如何申請退換貨</h3>
                                    <p>1.由於個人原因產生退換貨：至收到商品之日起30天內，在不影響二次銷售的情況下向售後服務中心發送郵件，售後客服會在收到郵件後的1-3個工作日內受理您的請求，退換貨所產生的運費需自行承擔</p>
                                    <p>2.由於質量原因產生的退換貨：至收到商品之日起7天內，向售後服務中心發送郵件至supportth@kingdomskymall.com,客服會在收到郵件後1-3個工作日內受理您的請求，退換貨所產生的運費由我方承擔</p>
                                    <p>3.退換貨流程：確認收貨---申請退換貨---客服審核通過-用戶寄回商品---倉庫簽收驗貨---退換貨審核---退款/換貨</p>
                                    <p>退換貨請註明：訂單號 姓名 電話</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <br><br><br><br>
                    <!-- 用户须知 E -->
                    <div class="details-footer" style="display: none">
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
                <a class="tab-item" href="#cart-attr"><span></span><span>立即購買(Order)</span></a>
            </nav>
        </div>

        <!--Cart1 begin-->
        <div class="page" id="cart-attr">
            <header class="bar bar-nav" style="background-color: #000;">
                <a class="icon icon-left pull-left" href="#home"></a>
                <h1 class="title">確認下單信息</h1>
            </header>
            <div class="content">
                <form class="details-form" action="javascript:;">
                    <div class="main-bg">
                        <div class="main">
                            <?php
                            if($website->is_group):
                            ?>
                            <div class="main-box">
                                <div class="label">產品</div>
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
                                    <div class="label">尺碼</div>
                                    <div class="operation-box"></div>
                                </div>
                                <div id="colorBox" class="main-box colorBox">
                                    <div class="label">顏色</div>
                                    <div class="operation-box"></div>
                                </div>

                                <div class="details-second-price">
                                    <div id="secondSizeBox" class="main-box">
                                        <div class="label">尺碼</div>
                                        <div class="operation-box"></div>
                                    </div>
                                    <div id="secondColorBox" class="main-box">
                                        <div class="label">顏色</div>
                                        <div class="operation-box"></div>
                                    </div>
                                </div>
                                <div class="details-second-price-btn"></div>
                            <?php endif;?>


                            <div class="main-box">
                                <div class="label">小計</div>
                                <div class="number-box">
                                    <b class="fl">-</b>
                                    <input class="num" type="text" name="number" readonly="readonly" value="1">
                                    <b class="fl">+</b>
                                    <strong id="totalPrice"><!-- <em class="rmb">₱</em><em>2300.00</em> --></strong>
                                </div>
                            </div>
                        </div>
                        <div class="submit-btn">
                            <a href="#cart-form">馬上搶</a>
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
                <h1 class="title">確認下單信息</h1>
            </header>
            <div class="content">
                <form class="details-form" action="javascript:;">
                    <div id="inputBox" class="main-bg">
                        <div class="main">
                            <div class="main-box">
                                <div class="label">* 姓名</div>
                                <div class="operation-box">
                                    <input type="text" name="inputName" placeholder="請填寫您的全名">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* 手機</div>
                                <div class="operation-box">
                                    <input type="text" name="mobile" placeholder="+886 示例 0912345678">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">郵箱</div>
                                <div class="operation-box">
                                    <input type="text" name="email" placeholder="郵箱（選填）">
                                </div>
                            </div>

                            <div class="main-box">
                                <div class="label">* 縣市</div>
                                <div class="operation-box">
                                    <select name="city" onchange="getArea('<?=$website->sale_city?>', this)" id="city_select">
                                        <option value="">縣市</option>
                                    </select>
                                    <input type="hidden" name="province" id="province" value="台灣">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* 鄉鎮市區</div>
                                <div class="operation-box">
                                    <select onchange="setPostCode(this)" id="area_select">
                                        <option value="">鄉鎮市區</option>
                                    </select>
                                    <input type="hidden" id="area_val" name="area" >
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* 詳細地址</div>
                                <div class="operation-box">
                                    <input type="text" name="address" placeholder="XX路XX號">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">留言</div>
                                <div class="operation-box">
                                    <textarea name="comment" placeholder="如備用聯系電話或多產品顏色"></textarea>
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">如何支付</div>
                                <div class="operation-box activity">
                                    <div class="activity-title">
                                        <em></em>貨到付款
                                    </div>
                                    <p>重要注意事項：在付款前檢查商品</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-btn">
                        <input class="button" type="submit" value="確認訂單">
                    </div>
                </form>
            </div>

        </div>
        <!--Cart2 end-->
        <!--popup-->
        <div class="popup popup-success">
            <div class="content-block">
                <h1>訂購成功</h1>
                <div class="card" style="min-height: 2rem;padding: 10px;">
                    <div class="card-content" style="padding:0 25px;">
                        <p style="text-align:center"><img width="100px" src="/images/th_success-icon.png"></p>
                        <p style="color:#339966;font-size: 1rem;text-align:center;">訂購成功</p>
                        <p style="color:#ff7300;font-size: 0.8rem;text-align:center">
                            訂單號: <span id="order_id"></span><br>
                            你已成功搶購。<br>
                            請確保您的手機時刻保持開機狀態，我們會盡快送達到您手上。
                        </p>
                        <p style="color: red;">
                            注意：在我們的網站上，付款方式僅為貨到付款。我們不會要求客戶將錢轉賬給我們或以任何理由使用其它付款方式
                        </p>
                        聯繫我們:<br>
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