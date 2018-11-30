<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>商品详情页</title>
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
</head>
<body>
    <div class="page-group">
        <div id="home" class="page page-current">
            <div class="content">
                <div class="details-wrap">
                    <div class="details-banner">
                        <div class="swiper-container" data-space-between='10'>
                            <div id="swiperWrapper" class="swiper-wrapper">
                            </div>
                        </div>
                        <div class="discount"> - 39%</div>
                    </div>
                    <div class="detail">
                        <div class="detail-title"><h1></h1></div>
                        <div class="rating">
                            <div class="stars"></div>96% เปอร์เซ็นต์คำนิยม
                        </div>
                    </div>
                    <div class="detail">
                        <div class="price">
                            <!-- <del>฿2550</del>
                            <span class="discount gree">พิเศษ 39%</span>
                            <div>
                                <span>฿<span>1550</span></span>
                            </div> -->
                        </div>
                    </div>
                    <div class="detail_arrow_con">
                        <a href="#cart-attr" class="arrow_d" data-cuckootag="buy_now">เลือกข้อกำหนด</a>
                    </div>
                    <div class="detail-expand">
                        <div class="left" data-cuckootag="check_order" data-value="hzhbag23"><span class="inquiry">ตรวจสอบสถานะการ</span></div>
                    </div>
                    <!-- product_info S -->
                    <div class="product_info">
                        <div class="tt">
                            <div class="tt-title">รายละเอียดสินค้า</div>
                            <div class="kefu"></div>
                        </div>
                        <div id="info" class="render_content">
                            <!-- <p style="text-align: left; line-height: 3em;"><span style="font-family: Calibri; font-size: 20px;"></span></p>
                            <p style="text-align: center;"><span style="color: rgb(255, 0, 0); font-size: 24px;"><strong>ข่าวดี ！ข่าวดี！ข่าวดี！</strong></span></p>
                            <p><br></p>
                            <p style="text-align: center;">ราคาโปรโมชัน มีแค่ 1000 คูกระเป๋าใส่หน้าอก</p>
                            <p><br></p>
                            <p style="text-align: center;">เพื่อทำโปรโมชั่นเพื่อเฉลิมฉลองครบรอบปีของบริษัท ฯ&nbsp;</p>
                            <p><br></p>
                            <p style="text-align: center;">ราคาเดิม 1980 &nbsp;ตอนนี้แค่ 1280 เท่านั้น</p>
                            <p><br></p>
                            <p style="text-align: center;">และอีก ถ้าซื้อ 2 &nbsp;<span style="color: rgb(0, 0, 0);"><strong style="text-align: center; white-space: normal;">คูกระเป๋าใส่หน้าอก</strong></span> 2 ลด 55% &nbsp;หมายถึง&nbsp;</p>
                            <p style="text-align: center;"><strong><span style="color: rgb(255, 0, 0); font-size: 18px;"><br></span></strong></p>
                            <p style="text-align: center;"><span style="font-size: 16px;"><strong><span style="color: rgb(255, 0, 0);">1 คูกระเป๋าใส่หน้าอก 1280 &nbsp; 2 คูกระเป๋าใส่หน้าอก 1999 &nbsp;3&nbsp;คูกระเป๋าใส่หน้าอก 2599！</span></strong></span></p>
                            <p><br></p>
                            <p style="text-align: center;">ตอนนี้ได้จำหน่ายจำนวน 632 กระเป๋า 1000 กระเป๋า จะเปลียนเป็นราคาเดิม1980</p>
                            <p><img class="" src="images/info_pic1.jpg"></p>
                            <p style="line-height: 3em;"><span style="font-size: 20px;">ภาพของจริง100% ลูกค้า99%ที่ได้รับของกล่าวว่าของจริงสวยก่วารูปภาพ&nbsp;</span></p>
                            <p style="line-height: 3em;"><span style="font-size: 20px;"></span></p>
                            <p><span style="font-family: arial, helvetica, sans-serif;"><span style="letter-spacing: 0px; font-size: 21px; background: rgb(255, 255, 255);">จากความเชื่อถือของลูกค้า&nbsp; （</span><span style="background-color: rgb(255, 255, 255); font-size: 21px; letter-spacing: 0px;">ขโมยรูปต้องถูกสอบสวนความรับผิดตามกฎหมาย）</span></span></p>
                            <p style="line-height: 3em;"><img class="" src="images/info_pic2.jpg"></p>
                            <p><span style=";font-family:宋体;font-size:14px">ปัญหาทั่วไป</span></p>
                            <p><span style=";font-family:宋体;font-size:14px">Q. สินค้าทั้งหมดในเว็บไซต์เป็นของแท้หรือเปล่า</span></p>
                            <p><span style=";font-family:宋体;font-size:14px">A. เรายึดมั่นในการควบคุมทุกรายละเอียดของสินค้าอย่างเข้มงวด เช่น แหล่งกำเนิด วัตถุดิบ กระบวนการผลิตสินค้า ฯลฯ เพื่อช่วยให้ผู้บริโภคเลือกสินค้าที่มีคุณภาพมากที่สุด คุณสามารถเลือกซื้อได้อย่างมั่นใจ</span></p>
                            <p><span style=";font-family:宋体;font-size:14px">Q. ใช้ยานพาหนะอะไรมาขนส่งสินค้า</span></p>
                            <p><span style=";font-family:宋体;font-size:14px">A. โดยทั่วไปแล้ว เราจะใช้เครื่องบินมาขนส่งสินค้า</span></p>
                            <p><span style=";font-family:宋体;font-size:14px">Q. วัสดุของฉันจะ ใช้เวลากี่วันในการขนส่ง</span></p>
                            <p><span style=";font-family:宋体;font-size:14px">A.</span></p>
                            <p><span style=";font-family:宋体;font-size:14px">1. เราจะเริ่มจัดส่งสินค้าภายใน10-15 วันหลังจากที่คุณสั่งซื้อสำเร็จ (ยกเว้นวันหยุด).</span></p>
                            <p><span style=";font-family:宋体;font-size:14px">2. ถ้ามีเหตุการฉุกเฉิน เวลาส่งถึงอาจมีความล่าช้าเล็กน้อย</span></p>
                            <p><span style=";font-family:宋体;font-size:14px">3.เนื่องจากว่าโกดังที่เก็บสินค้าอยู่ในแต่ละที่ สินค้าที่อยู่ในแพคเกจเดียวกันอาจจะถูกแบ่งออกเป็นหลายพัสดุ เวลาที่ส่งถึงของแต่ละพัสดุอาจไม่เหมือนกัน&nbsp;</span></p> -->
                        </div>
                    </div>
                    <!-- product_info E -->


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
                <a class="tab-item" href="#cart-attr"><span></span><span>Beli Sekarang </span></a>
            </nav>
        </div>
        <!--Cart1 begin-->
        <div class="page" id="cart-attr">
            <header class="bar bar-nav" style="background-color: #000;">
                <a class="icon icon-left pull-left" href="#home"></a>
                <h1 class="title">Konfirmasikan Pesanan</h1>
            </header>
            <div class="content">
                <form class="details-form" action="javascript:;">
                    <div class="main-bg">
                        <div class="main">
                            <?php
                            if($website->is_group):
                            ?>
                            <div class="main-box">
                                <div class="label">Pilihan produk</div>
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
                                    <div class="label">Ukuran</div>
                                    <div class="operation-box"></div>
                                </div>
                                <div id="colorBox" class="main-box colorBox">
                                    <div class="label">Warna</div>
                                    <div class="operation-box"></div>
                                </div>
                                
                                <div class="details-second-price">
                                    <div id="secondSizeBox" class="main-box">
                                        <div class="label">Ukuran</div>
                                        <div class="operation-box"></div>
                                    </div>
                                    <div id="secondColorBox" class="main-box">
                                        <div class="label">Warna</div>
                                        <div class="operation-box"></div>
                                    </div>
                                </div>
                                <div class="details-second-price-btn"></div>
                            <?php endif;?>


                            <div class="main-box">
                                <div class="label">Jumlah</div>
                                <div class="number-box">
                                    <b class="fl">-</b>
                                    <input class="num" type="text" name="number" readonly="readonly" value="1">
                                    <b class="fl">+</b>
                                    <strong id="totalPrice"><!-- <em class="rmb">₱</em><em>2300.00</em> --></strong>
                                </div>
                            </div>
                        </div>
                        <div class="submit-btn">
                            <a href="#cart-form">NEXT</a>
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
                <h1 class="title">Konfirmasikan Pesanan</h1>
            </header>
            <div class="content">
                <form class="details-form" action="javascript:;">
                    <div id="inputBox" class="main-bg">
                        <div class="main">
                            <div class="main-box">
                                <div class="label">* nama</div>
                                <div class="operation-box">
                                    <input type="text" name="inputName" placeholder="silahkan input nama Anda">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* nomor telepon</div>
                                <div class="operation-box">
                                    <input type="text" name="mobile" placeholder="（08 XXXX XXXX）">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">E-Mail</div>
                                <div class="operation-box">
                                    <input type="text" name="email" placeholder="silahkan input alamat email Anda">
                                </div>
                            </div>
                            <div class="main">
                            <div class="main-box">
                                <div class="label">* provinsi</div>
                                <div class="operation-box">
                                    <?php

                                    $provinces = \app\models\ShipingArea::findAll(['country' => $website->sale_city]);
                                    if($provinces):
                                        $options = [];
                                        foreach($provinces as $p)
                                        {
                                            $options[$p->province] = $p->province;
                                        }
                                        echo \yii\helpers\Html::dropDownList('province', '', ['' => 'provinsi'] + $options, ['onchange'=>'getCity("'.$website->sale_city.'", this)', 'id' => 'province']);
                                    else:
                                    ?>
                                    <input type="text" name="province" id="province" placeholder="provinsi">
                                    <?php
                                    endif;
                                    ?>
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* kota/daerah istimewah</div>
                                <div class="operation-box">
                                    <select name="city" onchange="getArea('<?=$website->sale_city?>', this)" id="city_select">
                                        <option value="">kota/daerah istimewah</option>
                                    </select>
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* daerah</div>
                                <div class="operation-box">
                                    <select onchange="setPostCode(this)" id="area_select">
                                        <option value="">daerah</option>
                                    </select>
                                    <input type="hidden" id="area_val" name="area" >
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* alamat rinci</div>
                                <div class="operation-box">
                                    <input type="text" name="address" placeholder="menyaran anda mengisi alamat perusahaan, nyaman terima paket.">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">* kode pos</div>
                                <div class="operation-box">
                                    <input type="text" id="postCode" name="postCode">
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">konten pesan kepada penjual</div>
                                <div class="operation-box">
                                    <textarea name="comment" placeholder="konten pesan kepada penjual"></textarea>
                                </div>
                            </div>
                            <div class="main-box">
                                <div class="label">cara membayar</div>
                                <div class="operation-box activity">
                                    <div class="activity-title">
                                        <em></em>uang muka pengiriman
                                    </div>
                                    <p>perhatian penting: cek sebelum Anda membayar barang</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-btn">
                        <input class="button" type="submit" value="beli sekarang ">
                    </div>
                </form>
            </div>
        </div>
        <!--Cart2 end-->
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
    <script>
        var end_timestamp_num = 6;
    </script>
    <script src="/themes/<?=$website->theme;?>/js/shop_details.js"></script>
</body>
</html>