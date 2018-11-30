<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title><?php echo($product->title); ?></title>
	<link rel="stylesheet" href="/themes/TH/css/sm.css">
    <link rel="stylesheet" href="/themes/TH/css/sm-extend.css">
    <link rel="stylesheet" href="/themes/TH/css/shop.css">
    <script>
    var end_timestamp_num = <?php echo($product->sale_end_hours);  ?>;
    var salePrice = <?php echo($product->sale_price);  ?>;
    </script>
</head>
<body>
	<div class="page-group details">
		<div class="page page-current">
			<div class="content">
				<div class="title"><?php echo($product->title); ?></div>
				<div class="swiper-container" data-space-between='10'>
					<div id="swiperWrapper" class="swiper-wrapper">
                        <?php
                        $images = json_decode($product->images);
                        foreach ($images as $image) {
                            echo '<div class="swiper-slide"><img src="', $image ,'"></div>';
                        }
                        ?>
					</div>
				</div>
				<div class="xd-price-bg">
					<div class="details-price">
						<div class="row no-gutter">
							<div class="col-40"><?php echo($product->sale_price); ?></div>
							<div class="col-60">
								<div class="row padding-style">
									<div class="col-33"><em>Price</em><br><i class="i"><?php echo($product->price); ?></i></div>
									<div class="col-33"><em>Discount</em><br><i><?php echo(round($product->sale_price / $product->price, 2)); ?></i></div>
									<div class="col-33"><em>Save</em><br><i><?php echo($product->price - $product->sale_price); ?></i></div>
								</div>
							</div>
						</div>
					</div>
					<div class="details-price-label"><span>Time Limited</span><span>Free Shipment</span><span>Cash On Delivery</span><span>No Reason Return/Change in 7 Days</span></div>
					<div class="details-price-time">
						<div class="text"><?php echo($orderCount); ?>People Placed Orders</div>
						<div id="remainTime" class="jltimer">
                            
	                        <span id="hour"></span>H
	                        <span id="min"></span>M
	                        <span id="sec"></span>S
	                    </div>
					</div>
					<div class="details-price-foot"><a href="#" class="button">Shop Now</a></div>
				</div>
				<div class="details-show-pic">
                <?php echo($product->info); ?>
					<p><img src="/themes/TH/images/details_pic1.jpg"></p>
					<p><img src="/themes/TH/images/details_pic2.gif"></p>
				</div>
				<div class="details-reviews">
					<div class="details-bwtitle">Reviews</div>
					<div class="list-block list-li-marquee1">
						<ul style="top: 0;">
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
				</div>
				<form class="details-form" action="###">
					<div class="details-bwtitle">Order Information</div>
					<div class="main-bg">
						<div class="main">
                            <?php
                            if ($product->is_group == 1)
                            {
                            ?>
							<div class="main-box">
								<div class="label">Product Option</div>
								<div class="operation-box"><span class="active">CLASSIC FASHIONABLE ELEGANT MOCCASINS(2 pairs + 2 pairs of anti-odor socks -Random Color-black/white/gray )</span></div>
                            </div>
                            <?php
                            }
                            ?>
							<div class="main-box">
								<div class="label">SIZE( if different size for two pairs,mes noted )</div>
								<div class="operation-box">
                                    <?php
                                    foreach ($sizeData as $size)
                                    {
                                        echo '<span>', $size  ,'</span>';
                                    }
                                    ?>
                                </div>
							</div>
							<div class="main-box">
								<div class="label">COLOR</div>
								<div class="operation-box">
                                    <?php
                                    foreach ($colorInfo as $color)
                                    {
                                        echo '<span>', $color['name'],'</span>';
                                    }
                                    ?>
                                </div>
							</div>
							<div class="main-box">
								<div class="label">Number</div>
								<div class="number-box">
                                    <b class="fl">+</b><input class="num" type="text" readonly="readonly" value="1"><b class="fl">-</b>
									<strong><em class="rmb">₱</em><em>2300.00</em></strong>
								</div>
							</div>
						</div>
					</div>
					<div class="main-bg">
						<div class="main">
							<div class="main-box">
								<div class="label">*Name</div>
								<div class="operation-box">
									<input type="text" placeholder="Please input your name">
								</div>
							</div>
							<div class="main-box">
								<div class="label">*Phone</div>
								<div class="operation-box">
									<input type="text" placeholder="Please input your phone number">
								</div>
							</div>
							<div class="main-box">
								<div class="label">E-Mail</div>
								<div class="operation-box">
									<input type="text" placeholder="Please input your email">
								</div>
							</div>
							<div class="main-box">
								<div class="label">*Province</div>
								<div class="operation-box">
									<select>
										<option value="0">select</option>
										<option value="Abra">Abra</option>
										<option value="Agusan Del Norte">Agusan Del Norte</option>
									</select>
								</div>
							</div>
							<div class="main-box">
								<div class="label">*City/Municipality</div>
								<div class="operation-box">
									<select>
										<option value="0">select</option>
									</select>
								</div>
							</div>
							<div class="main-box">
								<div class="label">*Barangay</div>
								<div class="operation-box">
									<input type="text">
								</div>
							</div>
							<div class="main-box">
								<div class="label">*Detail Address</div>
								<div class="operation-box">
									<input type="text" placeholder="Address in detail">
								</div>
							</div>
							<div class="main-box">
								<div class="label">*Postal Code</div>
								<div class="operation-box">
									<input type="text">
								</div>
							</div>
							<div class="main-box">
								<div class="label">Message to Seller(optional)</div>
								<div class="operation-box">
									<textarea placeholder="Please deliver goods as soon as possible"></textarea>
								</div>
							</div>
							<div class="main-box">
								<div class="label">Payment Method</div>
								<div class="operation-box activity">
									<div class="activity-title"><em></em>Cash On Delivery</div>
									<p>Warm Notes：Check before you pay for the goods</p>
								</div>
							</div>
						</div>
					</div>
					<div class="submit-btn">
						<input class="button" type="submit" value="Shop Now">
					</div>
				</form>
				<div class="details-shipment-info">
					<div class="details-bwtitle">Shipment Info</div>
					<div class="shipment-info-box">
						<div class="list-block list-li-marquee2">
							<ul>
								<li>2018-7-1&nbsp;Rash***（13793****）Tempahan anda 【CLASSIC FASHIONABLE ELEGANT MOCCASINS(2 pairs + 2 pairs of anti-odor socks -Random Color-black/white/gray )】telah dihantar √ Sila semakan. </li>
								<li>测试数据1</li>
								<li>测试数据2</li>
								<li>测试数据3</li>
								<li>测试数据4</li>
								<li>测试数据5</li>
								<li>测试数据6</li>
								<li>测试数据7</li>
								<li>测试数据8</li>
								<li>测试数据9</li>
								<li>测试数据10</li>
								<li>测试数据11</li>
								<li>测试数据12</li>
								<li>测试数据13</li>
								<li>测试数据14</li>
								<li>测试数据15</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="details-footer"><img src="/themes/TH/images/details_footer.jpg"></div>
			</div>
			<nav class="bar bar-tab details-nav">
				<a class="tab-item" href="#"><span></span><span>Shop Now</span></a>
				<a class="tab-item alert-text" href="#"><span></span><span>CONTACT US</span></a>
			</nav>
		</div>
	</div>
	<script type='text/javascript' src='/themes/TH/js/zepto.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='/themes/TH/js/sm.js' charset='utf-8'></script>
    <script type='text/javascript' src='/themes/TH/js/sm-extend.js' charset='utf-8'></script>
    <script src="/themes/TH/js/shop_details.js"></script>
</body>
</html>