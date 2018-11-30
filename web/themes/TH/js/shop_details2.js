$(function (){
	var $detailsForm = $('.details-form');
	var $inputs = $('#inputBox').find('input');
	// banner
	$('.swiper-container').swiper({
		onInit: function (swiper){
			var $slides = swiper.slides;
			$('.swiper-container .page-num span:nth-of-type(2)').text($slides.length);
		},
		onSlideChangeEnd: function (swiper){
			$('.swiper-container .page-num span:nth-of-type(1)').text(swiper.activeIndex + 1);
		}
	});
	// 计时器
    var this_time = Date.parse(new Date()) / 1000;
    var end_timestamp = end_timestamp_num * 3600 + this_time;
   	var timedCount = function (){
    	this_time = this_time + 1;
        var sub_all_sec = end_timestamp - this_time;
        var sub_day = parseInt(sub_all_sec / 86400);
        var sub_hour = parseInt((sub_all_sec % 86400) / 3600);
        var sub_min = parseInt((sub_all_sec % 3600) / 60);
        var sub_sec = parseInt(sub_all_sec % 60);
        if (sub_all_sec > 0){
        	 setTimeout(function (){
	        	timedCount();
	        }, 1000);
        } else {
        	sub_day = 0;
        	sub_hour = 0;
        	sub_min = 0;
        	sub_sec = 0;
        }
        // if(sub_day < 10){
        //     sub_day = "0" + sub_day;
        // }
        if (sub_day > 0){
            sub_hour = sub_day * 24 + sub_hour;
        }
        if(sub_hour < 10){
            sub_hour = "0" + sub_hour;
        }
        if(sub_min < 10){
            sub_min = "0" + sub_min;
        }
        if(sub_sec < 10){
            sub_sec = "0" + sub_sec;
        }
        $("#day").html(sub_day);
        $("#hour").html(sub_hour);
        $("#min").html(sub_min);
        $("#sec").html(sub_sec);   
    }
    timedCount();
    // tab
    var $content = $('.content');
    var detatwoHeadHeight= $('.detatwo-head').outerHeight();
    var $detatwoBars = $('.detatwo-bars');
    var detatwoBarsHeight = $detatwoBars.outerHeight();
    var $detatwoContext = $('.detatwo-context');
    var detatwoBarsTop = $detatwoBars.offset().top - detatwoHeadHeight;
    var detatwoContextTopArr = [];
    $content.scroll(function (){
    	var contentScrollTop = $(this).scrollTop();
    	if (detatwoBarsTop <= contentScrollTop){
    		$detatwoBars.addClass('fixed');
    	} else {
    		$detatwoBars.removeClass('fixed');
    	}
    });
    $detatwoBars.find('li').each(function (index){
    	var detatwoContextTop = $detatwoContext.eq(index).offset().top - detatwoHeadHeight - detatwoBarsHeight * 2;
    	detatwoContextTopArr.push(detatwoContextTop);
    	$(this).click(function (){
    		$content.scrollTop(detatwoContextTopArr[index]);
    	});
    });
    // 订单
    var $orderList = $('.order-list');
    var $orderListLis = $orderList.find('li');
    $orderListLis.filter(function (index){
        if (index % 2 == 0){
            return false;
        } else {
            return true;
        }
    }).css('background-color', '#eee');
    setLiMarquee($orderList);
    function setLiMarquee(eleObj){
        var $ul = eleObj.find('ul');
        var $liFirst = eleObj.find('li:first-child');
        var liFirstHeight = $liFirst.outerHeight();
        var ulTopNum = parseFloat($ul.css('top'));
        ulTopNum--;
        if (ulTopNum == -liFirstHeight){
            ulTopNum = 0;
            $liFirst.remove();
            $ul.append($liFirst);
        }
        $ul.css('top', ulTopNum);
        setTimeout(function (){
            setLiMarquee(eleObj);
        }, 30);
    }
    // footer-icon
    var popupTextArray = [
        {
            text: 'เกี่ยวกับเรา<br>พวกเราให้ความสำคัญในด้านการเลือกที่ผลิต งานฝีมือและวัตถุดิบของสินค้าอย่างเข้มงวด เช่น เครื่องแต่งกาย กระเป๋า เครื่องครัว เครื่องกีฬา เพื่อให้สินค้าที่มีคุณภาพดีที่สุดกับคุณ.<br><button type="button" class="button">Back</button>'
        },
        {
            text: 'การแจ้งเตือนผู้ใช้<br>การใช้ผลิตภัณฑ์นี้จะขึ้นอยู่กับแต่ละสถานการณ์ ไม่มีการรับประกันว่าผู้ใช้ทุกคนจะได้รับผลลัพธ์ที่โฆษณา หากมีข้อสงสัย กรุณาติดต่อฝ่ายบริการลูกค้าออนไลน์หรือติดต่อทาง e-mail (supportth@kingdomskymall.com)  บริษัทของเรามีสิทธิในการตีความ.<br><button type="button" class="button">Back</button>'
        },
        {
            text: 'รายละเอียดการจัดส่ง<br>ทางเราจะจัดส่งสินค้าภายใน 3 วันโดยตามลำดับหลังจากสั่งซื้อสินค้าสำเร็จ และจะต้องใช้ระยะเวลาอีก 10 วันสำหรับการขนส่ง<br><button type="button" class="button">Back</button>'
        },
        {
            text: 'วิธีการติดต่อ<br>บริการลูกค้าออนไลน์ตลอด 24 ชม：<br>อีเมล：<span>supportth@kingdomskymall.com</span><br />หากคุณมีคำถามใด ๆ  โปรดติดต่อหรือปรึกษาบริการลูกค้าออนไลน์ของเรา ขอบคุณมาก.<br><button type="button" class="button">Back</button>'
        },
        {
            text: 'กระบวนการส่งคืน<br>วิธีการเปลี่ยน/คืน:<br>    1.การเปลี่ยน/คืนสินค้าส่วนตัว: ภายใน 7 วันนับจากวันที่ได้รับสินค้า โปรดติดต่อฝ่ายบริการลูกค้าออนไลน์ของเราหรือส่งอีเมลไปที่ supportth@kingdomskymall.com,โดยไม่มีผลต่อยอดขายรอง ฝ่ายบริการลูกค้าจะตอบแทนภายใน 1-3 วันหลังจากได้รับข้อความ คุณต้องการชำระเงินของค่าขนส่ง.<br>กระบวนการส่งคืน:<br>   ได้รับสินค้า - ใบสมัครสำหรับการรับคืน - การตรวจสอบการบริการลูกค้า - ส่งคืนสินค้า - การตรวจสอบฝ่ายคลังสินค้า - การตรวจสอบผลตอบแทน - การคืนเงิน / การแลกเปลี่ยนสินค้า      กรุณาระบุ: เลขที่ใบสั่งซื้อ ชื่อ เบอร์โทรศัพท์.<br><button type="button" class="button">Back</button>'
        },
        {
            text: 'สอบถามเกี่ยวกับโลจิสติกส์<br><button type="button" class="button">Back</button>'
        }
    ];
    var $popupFooterText = $('.popup-footer-text');
    $('.details-footer img').each(function (index){
        $(this).click(function (){
            $popupFooterText.find('p').html(popupTextArray[index].text);
            $.popup('.popup-footer-text');
        });
    });
    $popupFooterText.click(function (){
        $.closeModal('.popup-footer-text');
    });
    // 增删减
    var nowPriceNum = Number($('#nowPrice').text().replace(/[^0-9\.]/ig, ''));
    var $totalPrice = $('#totalPrice');
    var $inputNum = $('input[name=number]');
    var $numberBox;
    var valueNumer = 0;
    // $totalPrice.text(currency + nowPriceNum);
    $totalPrice.text(nowPriceNum);
    $('.details-form .main-box .number-box b:nth-of-type(1)').click(function (){
        valueNumer = $inputNum.val();
        valueNumer--;
        if (valueNumer <= 1){
            valueNumer = 1;
        }
        $inputNum.val(valueNumer);
        $totalPrice.text(valueNumer * nowPriceNum);
        // if(nextPrice>0){
        //     var ttp = nowPriceNum + (valueNumer-1) * nextPrice;
        //     $totalPrice.text(currency + ttp);
        // }else{
        //     $totalPrice.text(currency + valueNumer * nowPriceNum);
        // }

    });
    $('.details-form .main-box .number-box b:nth-of-type(2)').click(function (){
        valueNumer = $inputNum.val();
        valueNumer++;

        $inputNum.val(valueNumer);
        $totalPrice.text(valueNumer * nowPriceNum);
        // if(nextPrice>0){
        //     var ttp = nowPriceNum + (valueNumer-1) * nextPrice;
        //     $totalPrice.text(currency + ttp);
        // }else{
        //     $totalPrice.text(currency + valueNumer * nowPriceNum);
        // }
    });
    // 提交
    var $mainBox = $detailsForm.find('.main-box');
    $mainBox.each(function (){
        var $spans = $(this).find('span');
        $spans.click(function (){
            $spans.removeClass('active');
            $(this).addClass('active');
        });
    });
    $detailsForm.submit(function (){
        for (var i = 0; i < $inputs.length; i++){
            var _input = $inputs.eq(i);
            if (_input.val() == '' && _input.attr('name')!='email'){
                _input.focus();
                return false;
            }
        }

        // var size = $('#sizeBox span.active').text();
        // var color = $('#colorBox span.active').text();
        // var num = $('input[name=number]').val();
        // var name = $('input[name=inputName]').val();
        // var mobile = $('input[name=mobile]').val();
        // var email = $('input[name=email]').val();
        // var province = $('#province').val();
        // var city = $('input[name="city"]').val();
        // var address = $('input[name=address]').val();
        // var postCode = $('input[name=postCode]').val();
        // var comment = $('textarea[name=comment]').val();
        // var totalPriceNum = $('#totalPrice').text().replace(/[^0-9\.]/ig, '');
        // $.ajax({
        //     type: 'post',
        //     url: '/shop/add/order',
        //     data: {
        //         host: requestId,
        //         is_group: isGroup,
        //         group_id: groupId,
        //         color: color,
        //         size: size,
        //         num: num,
        //         name: name,
        //         mobile: mobile,
        //         email: email,
        //         province: province,
        //         city: city,
        //         address: address,
        //         post_code: postCode,
        //         comment: comment,
        //         total_price: totalPriceNum,
        //         propertyInfo: JSON.stringify(propertyInfo)
        //     },
        //     dataType: 'json',
        //     success: function (res){
        //         fbq('track', 'Purchase');
        //         $("#order_id").html('JTNX' + res.orderId);
        //         $("#order_price").html(res.total);
        //         $.popup('.popup-success');
        //     }
        // });   
    });
});