<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title><?=$website->title;?></title>
	<link rel="stylesheet" href="/themes/TH/css/sm.css">
    <link rel="stylesheet" href="/themes/TH/css/sm-extend.css">
	<link rel="stylesheet" href="/themes/TH/css/shop.css">
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
        .title{font-weight: bold;}
    </style>
</head>
<body>
	<div class="page-group details">
		<div class="page page-current" id="home">
			<div class="content">

				<div class="swiper-container" data-space-between='10'>
					<div id="swiperWrapper" class="swiper-wrapper">
						<!-- <div class="swiper-slide"><img src="/themes/TH/images/details_banner1.jpg"></div>
						<div class="swiper-slide"><img src="/themes/TH/images/details_banner2.jpg"></div>
						<div class="swiper-slide"><img src="/themes/TH/images/details_banner3.jpg"></div> -->
					</div>
				</div>
                <div class="title" style="white-space: inherit;"></div>
				<div class="xd-price-bg details-price-box">
					<!-- <div class="details-price">
						<div class="row no-gutter">
							<div class="col-40">₱2300</div>
							<div class="col-60">
								<div class="row padding-style">
									<div class="col-33"><em>ราคา</em><br><i class="i">₱4600</i></div>
									<div class="col-33"><em>ส่วนลด</em><br><i>5</i></div>
									<div class="col-33"><em>ประหยัด</em><br><i>₱2300</i></div>
								</div>
							</div>
						</div>
					</div> -->
					<!-- <div class="details-price-label"><span>Time Limited</span><span>Free Shipment</span><span>Cash On Delivery</span><span>No Reason Return/Change in 7 Days</span></div> -->
					<!-- <div class="details-price-time">
						<div class="text">2456People Placed Orders</div>
						<div id="remainTime" class="jltimer">
	                        <span id="hour"></span>H
	                        <span id="min"></span>M
	                        <span id="sec"></span>S
	                    </div>
					</div> -->
					<!-- <div class="details-price-foot"><a href="#" class="button">Shop Now</a></div> -->
				</div>

				<div class="details-show-pic">
                    <div id="info"></div>
                    <div id="attr_info"></div>
				</div>
				<!--<div class="details-reviews">
					<div class="details-bwtitle">ความคิดเห็น</div>
					<div class="list-block list-li-marquee1">
						<ul>
							<li>
								<p>These shoes are very comfortable and very light weight.</p>
								<div class="information clearfix">
									<span class="fl">arif***（10409****）</span>
									<span class="fr">2018-6-29</span>
								</div>
							</li>
							<li>
								<p>I like these shoes - they are a great distinctive style that can be worn with a suit and with just a shirt or sport jacket in less formal situations.</p>
								<div class="information clearfix">
									<span class="fl">arif***（10409****）</span>
									<span class="fr">2018-6-29</span>
								</div>
							</li>
							<li>
								<p>Husband loves his new shoes. He hates to go to the local stores to purchase anything. We were very pleased these were what he wanted.</p>
								<div class="information clearfix">
									<span class="fl">arif***（10409****）</span>
									<span class="fr">2018-6-29</span>
								</div>
							</li>
							<li>
								<p>Bought as a gift for my husband, look fantastic and he says they are extremely comfortable.</p>
								<div class="information clearfix">
									<span class="fl">arif***（10409****）</span>
									<span class="fr">2018-6-29</span>
								</div>
							</li>
							<li>
								<p>Color is okay. Bought this for my husband, who loves this style of shoe. He is happy with the shoes</p>
								<div class="information clearfix">
									<span class="fl">arif***（10409****）</span>
									<span class="fr">2018-6-29</span>
								</div>
							</li>
							<li>
								<p>These shoes are PHENOMENAL. They look great and are extremely comfortable.</p>
								<div class="information clearfix">
									<span class="fl">arif***（10409****）</span>
									<span class="fr">2018-6-29</span>
								</div>
							</li>
							<li>
								<p>Great product; great price.</p>
								<div class="information clearfix">
									<span class="fl">arif***（10409****）</span>
									<span class="fr">2018-6-29</span>
								</div>
							</li>
							<li>
								<p>Very nice shoe. Fits as expected, very nice quality especially for the price. I would recommend this shoe.</p>
								<div class="information clearfix">
									<span class="fl">arif***（10409****）</span>
									<span class="fr">2018-6-29</span>
								</div>
							</li>
							<li>
								<p>Very comfortable and very easy to wear all day~</p>
								<div class="information clearfix">
									<span class="fl">arif***（10409****）</span>
									<span class="fr">2018-6-29</span>
								</div>
							</li>
							<li>
								<p>These are so comfortable! And they don't make my feet too warm like my older slippers that I wore. They look amazing and expensive! I got a compliment about them the other day someone asked if they were mocosins. I wear these during th day a lot when I go out cause it looks like I'm wearing really expensive shoes. Thank you seller!</p>
								<div class="information clearfix">
									<span class="fl">arif***（10409****）</span>
									<span class="fr">2018-6-29</span>
								</div>
							</li>
							<li>
								<p>Loved them! Soft and look amazing!</p>
								<div class="information clearfix">
									<span class="fl">arif***（10409****）</span>
									<span class="fr">2018-6-29</span>
								</div>
							</li>
						</ul>
					</div>
				</div>-->

                <!-- 评论 S -->
                <div class="details-reviews">
                    <div class="details-bwtitle">Reviews</div>
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
                    <img src="/themes/TH/images/details_footer_icon1.png">
                    <img src="/themes/TH/images/details_footer_icon2.png">
                    <img src="/themes/TH/images/details_footer_icon3.png">
                    <img src="/themes/TH/images/details_footer_icon4.png">
                    <img src="/themes/TH/images/details_footer_icon5.png">
                    <img src="/themes/TH/images/details_footer_icon6.png">
                </div>
			</div>
			<nav class="bar bar-tab details-nav">
				<!--a class="tab-item" target="_blank" href="https://put.zoosnet.net/LR/Chatpre.aspx?id=PUT13291804&lng=en" external><span></span><span>ติดต่อเรา</span></a-->
                <a class="tab-item" href="#cart"><span></span><span>ช็อปปิ้งตอนนี้</span></a>
			</nav>
		</div>
        <!--Cart begin-->
        <div class="page" id="cart">
            <header class="bar bar-nav" style="background-color: #000;">
                <a class="icon icon-left pull-left" href="#home"></a>
                <h1 class="title">ข้อมูลการสั่งซื้อ</h1>
            </header>
            <div class="content">
                <form class="details-form" action="javascript:;">
                    <div class="main-bg">
                        <div class="main">
                            <?php
                            if($website->is_group):
                            ?>
                            <div class="main-box">
                                <div class="label">ตัวเลือกผลิตภัณฑ์</div>
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
                                    <div class="label">ขนาด (ขนาดต่างกันสำหรับสองคู่โน้ต)</div>
                                    <div class="operation-box"></div>
                                </div>
                                <div id="colorBox" class="main-box colorBox">
                                    <div class="label">สี</div>
                                    <div class="operation-box"></div>
                                </div>

                                <div class="details-second-price">
                                    <div id="secondSizeBox" class="main-box">
                                        <div class="label">นาด (ขนาดต่างกันสำหรับสองคู่โน้ต)</div>
                                        <div class="operation-box"></div>
                                    </div>
                                    <div id="secondColorBox" class="main-box">
                                        <div class="label">สี</div>
                                        <div class="operation-box"></div>
                                    </div>
                                </div>
                                <div class="details-second-price-btn"></div>
                            <?php endif;?>


                            <div id="numberBox" class="main-box">
                                <div class="label">จำนวน</div>
                                <div class="number-box">
                                    <b class="fl">-</b>
                                    <input class="num" type="text" name="number" readonly="readonly" value="1">
                                    <b class="fl">+</b>
                                    <strong id="totalPrice"><!-- <em class="rmb">₱</em><em>2300.00</em> --></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="inputBox" class="main-bg">
                        <div class="main">
                            <div class="main-box">
                                <div class="label">* ชื่อ นามสกุล</div>
                                <div class="operation-box">
                                    <input type="text" name="inputName" placeholder="โปรดใส่ชื่อของคุณ">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* หมายเลขโทรศัพท์</div>
                                <div class="operation-box">
                                    <input type="text" name="mobile" placeholder="（09 XXXX XXXX）">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">E-Mail</div>
                                <div class="operation-box">
                                    <input type="text" name="email" placeholder="โปรดใส่อีเมลของคุณ">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* จังหวัด</div>
                                <div class="operation-box">
                                    <?php

                                    $provinces = \app\models\ShipingArea::findAll(['country' => $website->sale_city]);
                                    if($provinces):
                                        $options = [];
                                        foreach($provinces as $p)
                                        {
                                            $options[$p->province] = $p->province;
                                        }
                                        echo \yii\helpers\Html::dropDownList('province', '', ['' => 'กรุณาระบุจังหวัดของคุณ'] + $options, ['onchange'=>'getCity("'.$website->sale_city.'", this)', 'id' => 'province']);
                                    else:
                                    ?>
                                    <input type="text" name="province" id="province" placeholder="กรุณาระบุจังหวัดของคุณ">
                                    <?php
                                    endif;
                                    ?>
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* อำเภอ/เขต</div>
                                <div class="operation-box">
                                    <select onchange="setPostCode(this)" id="city_select">
                                        <option value="">กรุณาระบุเมือง / เขตเทศบาลของคุณ</option>
                                    </select>
                                    <input type="hidden" id="city_val" name="city" >
                                </div>
                            </div>

                            <div class="main-box">
                                <div class="label">* รายละเอียดที่อยู่</div>
                                <div class="operation-box">
                                    <input type="text" name="address" placeholder="ขอแนะนำเกรอกที่อยู่ทำงาน จะได้รับของอย่างสะดวกและรวกเร็วค่ะ">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* รหัสไปรษณีย์</div>
                                <div class="operation-box">
                                    <input type="text" id="postCode" name="postCode">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">ขหมายเหตุ</div>
                                <div class="operation-box">
                                    <textarea name="comment" placeholder="หมายเหตุ"></textarea>
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">วิธีการชำระเงิน</div>
                                <div class="operation-box activity">
                                    <div class="activity-title">
                                        <em></em>เก็บเงินปลายทาง
                                    </div>
                                    <p>หมายเหตุอบอุ่น: ตรวจสอบก่อนที่คุณจะชำระค่าสินค้า</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-btn">
                        <input class="button" type="submit" value="ช็อปปิ้งตอนนี้">
                    </div>
                </form>
            </div>

        </div>
        <!--Cart end-->
        <!--popup-->
        <div class="popup popup-success">
            <div class="content-block">
                <h1>สั่งซื้อสินค้าสำเร็จ</h1>
                <div class="card" style="min-height: 2rem;padding: 10px;">
                    <div class="card-content" style="padding:0 25px;">
                        <p style="text-align:center"><img width="100px" src="/images/th_success-icon.png"></p>
                        <p style="color:#339966;font-size: 1rem;text-align:center;">สั่งซื้อสินค้าเรียบร้อยแล้ว!</p>
                        <p style="color:#ff7300;font-size: 0.8rem;text-align:center">
                            *ไอดีการสั่งซื้อของคุณ:<span id="order_id"></span><br>
                            *เก็บเงินปลายทาง ยอดรวม:<span id="order_price"></span> บาท<p>
                        <p>สวัสดีค่ะ คุณลูกค้า <br/>ทางเราจะจัดส่งสินค้าอย่างรวดเร็ว กรุณาเปิดโทรศัพท์ไว้เพื่อให้คนส่งของติดต่อคุณได้ กรุณาอย่าสั่งซ้ำ หากคุณมีคำถามใดๆ กรุณาติดต่อทางอีเมล์ค่ะ </p>
                        <p style="color: red;font-size: 16px;">
                            หมายเหุต  วิธีการชำระเงินของบริษัทเรา สำหรับวิธีเก็บเงินปลายทางเท่านั้น บริษ้ทเราจะไม่รับวิธีโอนเงินหรือวิธีอื่นเพื่อให้ลูกค้าชำระเงินก่อนรับของ
                        </p>
                        *ติดต่อเรา:<br>
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
	<script type='text/javascript' src='/themes/TH/js/zepto.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='/themes/TH/js/sm.js' charset='utf-8'></script>
    <script type='text/javascript' src='/themes/TH/js/sm-extend.js' charset='utf-8'></script>
    <script type="text/javascript">
    	var end_timestamp_num = <?=$website->sale_end_hours?>;
    </script>
    <script src="/themes/TH/js/shop_details.js"></script>
</body>
<!-- Start of kingdomskymall Zendesk Widget script -->
<script>/*<![CDATA[*/window.zE||(function(e,t,s){var n=window.zE=window.zEmbed=function(){n._.push(arguments)}, a=n.s=e.createElement(t),r=e.getElementsByTagName(t)[0];n.set=function(e){ n.set._.push(e)},n._=[],n.set._=[],a.async=true,a.setAttribute("charset","utf-8"), a.src="https://static.zdassets.com/ekr/asset_composer.js?key="+s, n.t=+new Date,a.type="text/javascript",r.parentNode.insertBefore(a,r)})(document,"script","d77a113e-8676-48cc-bf2a-4e9d8c39a01f");/*]]>*/</script>
<!-- End of kingdomskymall Zendesk Widget script -->
</html>
