function getCity(country, obj) {
    $.getJSON("/country/get-city", {'country':country, 'province': $(obj).val()}, function (data) {
        $("#city_select option").remove();
        $("#postCode").val("");
        $("#city_select").append("<option value=''>city</option>");
        $.each(data, function(e,v){
            $("#city_select").append("<option value='" + v.city + "'>" + v.city + "</option>");
        });
    })
}
function getArea(country, obj) {
    var province = $("#province").val();
    $.getJSON("/country/get-area", {'country':country, 'province': province, city:$(obj).val()}, function (data) {
        $("#area_select option").remove();
        $("#postCode").val("");
        $("#area_select").append("<option value=''>Area</option>");
        $.each(data, function(e,v){
            $("#area_select").append("<option value='" + v.post_code + "'>" + v.area + "(" + v.post_code + ")</option>");
        });
    })
}
function setPostCode(obj) {
    $("#area_val").val($("#area_select option").not(function(){ return !this.selected }).text());
    $("#postCode").val($(obj).val());
}

function toThousands(num) {
    return num;
    // var result = [ ], counter = 0;
    // num = (num || 0).toString().split('');
    // for (var i = num.length - 1; i >= 0; i--) {
    //     counter++;
    //     result.unshift(num[i]);
    //     if (!(counter % 3) && i != 0) { result.unshift('.'); }
    // }
    // return result.join('');
}


$(function (){

    $(document).on("pageInit", function(e, pageId, $page) {
        console.log(pageId);
        switch (pageId){
            case 'home':
                console.log('home');
                fbq('track', 'PageView');
                break;
            case 'cart-attr':
                fbq('track', 'AddToCart');
                console.log('addtocart');
                break;
            case 'cart-form':
                fbq('track', 'InitiateCheckout');
                console.log('cartForm');
                break;
        }

    });

	var $detailsForm = $('.details-form');
	var $inputs = $('#inputBox').find('input');
    var $totalPrice = $('#totalPrice');
    // var requestId = 'shoes';   // 开发用，后续删除
    var priceContxtHtml = '';
    var detatwoNumberHtml = '';
    var detatwoBarsHtml = '';
    var tabContro = true;
    var groupContr = false;   // 是否为组合
    var groupId = 0;//组ID
    var isGroup = 0;//是否组合
    var propertyInfo = {};//组合产品信息
    var $detailsSecondPriceBtn = $('.details-second-price-btn');
    var $detailsSecondPrice = $('.details-second-price');
    var nextPrice = 0;//第二件价格
    var nextPriceContr = true;
    var $numberBox = $('.number-box');
    var sizeArr = [];
    var colorArr = [];
    var localStorageContr = localStorage.getItem('contral');
    if (localStorageContr){
        localStorageContr = false;
    } else {
        localStorageContr = true;
    }
    $.ajax({
        type: 'get',
        url: '/shop/api/' + requestId,
        dataType: 'json',
        success: function (res){
            isGroup = res.product.is_group;
            nextPrice = Number(res.product.next_price);
            var _product = res.product;
            var _images = _product.images;
            // 差价
            var _colorSupplement = res.colorSupplement; 
            var colorText = '';
            $('.detatwo-title').text(_product.sale_info + " " + _product.title);
            // $('.detatwo-sale-info').text(_product.sale_info);
            // banner
            for (var i = 0; i < _images.length; i++){
                var $swiperSlide = '<div class="swiper-slide"><img src="' + _images[i] + '"></div>';
                $('#swiperWrapper').append($swiperSlide);
            }
            $('.swiper-container').swiper({
                autoplay: 2000,
                autoplayDisableOnInteraction : false,
                onInit: function (swiper){
                    var $slides = swiper.slides;
                        $('.swiper-container .page-num span:nth-of-type(2)').text($slides.length);
                },
                onSlideChangeEnd: function (swiper){
                    $('.swiper-container .page-num span:nth-of-type(1)').text(swiper.activeIndex + 1);
                }
            });
            // price
            var salePrice = toThousands(_product.sale_price);
            priceContxtHtml = '<div class="dc-price">\
                                    <span>' + currency + '</span>\
                                    <span id="nowPrice">' + salePrice + '</span>\
                                    <span class="o-price">' + currency + toThousands(_product.price) + '</span>\
                                </div>\
                                <span class="flag">Free Shipping</span>\
                                <span class="flag">Cash On Delivery</span>';
            $('.detatwo-price .price-context').html(priceContxtHtml);
            // datatwo-number
            detatwoNumberHtml = '<span>' + res.orderCount + ' Sold</span>\
                                <span>\
                                    <span></span>\
                                </span>\
                                <span>89%</span>';
            $('.detatwo-number').html(detatwoNumberHtml);
            //info
            $("#info").html(_product.info);
            $("#attr_info").html(_product.additional);
            //图片加载失败，添加默认图片
            $('#swiperWrapper img, #swiperWrapper img, #info img, #attr_info img').error(function (){
                $(this).attr('src', '/themes/TH/images/details_banner1.jpg')
            });
            var nowPriceNum = Number($('#nowPrice').text().replace(/[^0-9]/ig, ''));
            var $totalPrice = $('#totalPrice');
            var _SupplementNowNum = nowPriceNum;
            var groupPrice = 0;
            var $inputNum = $('input[name=number]');
            var valueNumer = 1;
            // 组合
            if(_product.is_group == 1){
                groupId = $('#group_box span.active').attr('data-group');

                $.each(_product.groups, function(e, v){
                    if(v.group_id == groupId)
                    {
                        groupPrice = v.group_price;
                        $totalPrice.text(currency + toThousands(v.group_price));
                        var groupNumber = 0;
                        $.each(v.websites, function(we, wv){
                            var _size = wv.size;
                            if(_size.length>0)
                            {
                                var sizeHtml = '';
                                for (var i = 0; i < _size.length; i++){
                                    if (i == 0){
                                        var sizeSpans = '<span data-gnum="' + groupNumber + '" data-id="'+ wv.website_id +'" class="active">' + _size[i] + '</span>';
                                        if(propertyInfo[groupNumber] == undefined)
                                        {
                                            propertyInfo[groupNumber] = {};

                                        }
                                        propertyInfo[groupNumber]['website_id'] = wv.website_id;
                                        propertyInfo[groupNumber]['size'] = _size[i];

                                    } else {
                                        var sizeSpans = '<span data-gnum="' + groupNumber + '" data-id="'+ wv.website_id +'">' + _size[i] + '</span>';
                                    }
                                    // $('#sizeBox .operation-box').append(sizeSpans);
                                    sizeHtml += sizeSpans;
                                }
                                $("#group_options").append('<div class="main-box sizeBox">\n' + wv.title +
                                    '                                <div class="label">ukuran</div>\n' +
                                    '                                <div class="operation-box">' + sizeHtml + '</div>\n' +
                                    '                            </div>');

                            }else{
                                //$('.sizeBox').remove();
                            }

                            var _color = wv.color;
                            if(_color.length > 0){
                                var colorHtml = '';
                                for (var i = 0; i < _color.length; i++){
                                    if (i == 0){
                                        var colorSpans = '<span data-gnum="' + groupNumber + '" data-id="'+ wv.website_id +'" class="active color-image"><img src="' + _color[i].image + '">' + _color[i].name + '</span>';
                                        if(propertyInfo[groupNumber] == undefined)
                                        {
                                            propertyInfo[groupNumber] = {};

                                        }
                                        propertyInfo[groupNumber]['website_id'] = wv.website_id;
                                        propertyInfo[groupNumber]['color'] = _color[i].name;
                                    } else {
                                        var colorSpans = '<span data-gnum="' + groupNumber + '" data-id="'+ wv.website_id +'" class="color-image"><img src="' + _color[i].image + '">' + _color[i].name + '</span>';
                                    }
                                    colorHtml += colorSpans;
                                    // $('#colorBox .operation-box').append(colorSpans);
                                }
                                $("#group_options").append('<div class="main-box colorBox">\n' + wv.title +
                                    '                                <div class="label">warna</div>\n' +
                                    '                                <div class="operation-box">' + colorHtml + '</div>\n' +
                                    '                            </div>');

                            }else {
                                //$('.colorBox').remove();
                            }
                            groupNumber++;
                        });
                        $('.colorBox span').click(function(){
                            var group_num = $(this).attr('data-gnum');
                            if(propertyInfo[group_num] == undefined)
                            {
                                propertyInfo[group_num] = {};
                            }
                            propertyInfo[group_num]['website_id'] = $(this).attr('data-id');
                            propertyInfo[group_num]['color'] = $(this).text();
                        });
                        $('.sizeBox span').click(function(){
                            var group_num = $(this).attr('data-gnum');
                            if(propertyInfo[group_num] == undefined)
                            {
                                propertyInfo[group_num] = {};
                            }
                            propertyInfo[group_num]['website_id'] = $(this).attr('data-id');
                            propertyInfo[group_num]['size'] = $(this).text();
                        });

                        $.init();
                    }
                });

                $("#group_box span").click(function(){
                    groupId = $(this).attr('data-group');
                    $("#group_options").children().remove();
                    propertyInfo = {};

                    $.each(_product.groups, function(e, v){
                        if(v.group_id == groupId)
                        {
                            groupPrice = v.group_price;
                            $totalPrice.text(currency + toThousands(valueNumer * v.group_price));
                            var groupNumber = 0;
                            $.each(v.websites, function(we, wv){
                                var _size = wv.size;
                                if(_size.length>0)
                                {
                                    var sizeHtml = '';
                                    for (var i = 0; i < _size.length; i++){
                                        if (i == 0){
                                            var sizeSpans = '<span data-gnum="' + groupNumber + '" data-id="'+ wv.website_id +'" class="active">' + _size[i] + '</span>';
                                            if(propertyInfo[groupNumber] == undefined)
                                            {
                                                propertyInfo[groupNumber] = {};

                                            }
                                            propertyInfo[groupNumber]['website_id'] = wv.website_id;
                                            propertyInfo[groupNumber]['size'] = _size[i];
                                        } else {
                                            var sizeSpans = '<span data-gnum="' + groupNumber + '" data-id="'+ wv.website_id +'">' + _size[i] + '</span>';
                                        }
                                        // $('#sizeBox .operation-box').append(sizeSpans);
                                        sizeHtml += sizeSpans;
                                    }
                                    $("#group_options").append('<div class="main-box sizeBox">\n' + wv.title +
                                        '                                <div class="label">ukuran</div>\n' +
                                        '                                <div class="operation-box">' + sizeHtml + '</div>\n' +
                                        '                            </div>');

                                }else{
                                    //$('.sizeBox').remove();
                                }

                                var _color = wv.color;
                                if(_color.length > 0){
                                    var colorHtml = '';
                                    for (var i = 0; i < _color.length; i++){
                                        if (i == 0){
                                            var colorSpans = '<span data-gnum="' + groupNumber + '" data-id="'+ wv.website_id + '" class="active color-image"><img src="' + _color[i].image + '">' + _color[i].name + '</span>';
                                            if(propertyInfo[groupNumber] == undefined)
                                            {
                                                propertyInfo[groupNumber] = {};

                                            }
                                            propertyInfo[groupNumber]['website_id'] = wv.website_id;
                                            propertyInfo[groupNumber]['color'] = _color[i].name;
                                        } else {
                                            var colorSpans = '<span data-gnum="' + groupNumber + '" data-id="'+ wv.website_id +'" class="color-image"><img src="' + _color[i].image + '">' + _color[i].name + '</span>';
                                        }
                                        colorHtml += colorSpans;
                                        // $('#colorBox .operation-box').append(colorSpans);
                                    }
                                    $("#group_options").append('<div class="main-box colorBox">\n' + wv.title +
                                        '                                <div class="label">warna</div>\n' +
                                        '                                <div class="operation-box">' + colorHtml + '</div>\n' +
                                        '                            </div>');

                                }else {
                                    //$('.colorBox').remove();
                                }
                                groupNumber++;
                            });
                        }

                    });
                    $('.colorBox span').click(function(){
                        var group_num = $(this).attr('data-gnum');
                        if(propertyInfo[group_num] == undefined)
                        {
                            propertyInfo[group_num] = {};
                        }
                        propertyInfo[group_num]['website_id'] = $(this).attr('data-id');
                        propertyInfo[group_num]['color'] = $(this).text();
                    });
                    $('.sizeBox span').click(function(){
                        var group_num = $(this).attr('data-gnum');
                        if(propertyInfo[group_num] == undefined)
                        {
                            propertyInfo[group_num] = {};
                        }
                        propertyInfo[group_num]['website_id'] = $(this).attr('data-id');
                        propertyInfo[group_num]['size'] = $(this).text();
                    });
                    var $mainBox = $detailsForm.find('.main-box');
                            $mainBox.each(function (){
                                var $spans = $(this).find('span');
                                $spans.click(function (){
                                    $spans.removeClass('active');
                                    $(this).addClass('active');
                                });
                            });
                    $.init();
                });
            }else{
                var _size = res.sizeData;
                for (var i = 0; i < _size.length; i++){
                    if (i == 0){
                        var sizeSpans = '<span class="active">' + _size[i] + '</span>';
                    } else {
                        var sizeSpans = '<span>' + _size[i] + '</span>';
                    }
                    $('#sizeBox .operation-box').append(sizeSpans);
                    $('#secondSizeBox .operation-box').append(sizeSpans);
                }
                var _color = res.colorInfo;
                for (var i = 0; i < _color.length; i++){
                    if (i == 0){
                        var colorSpans = '<span class="active"><img src="' + _color[i].image + '">' + _color[i].name + '</span>';
                    } else {
                        var colorSpans = '<span><img src="' + _color[i].image + '">' + _color[i].name + '</span>';
                    }
                    $('#colorBox .operation-box').append(colorSpans);
                    $('#secondColorBox .operation-box').append(colorSpans);
                }
                colorText = $('#colorBox').find('span.active').text();
                _SupplementNowNum = nowPriceNum + _colorSupplement[colorText];
                $totalPrice.text(currency + _SupplementNowNum);
                if (nextPrice > 0){
                    $detailsSecondPriceBtn.text('NEXT ' + nextPrice);
                    $detailsSecondPriceBtn.show();
                    $numberBox.find('b, input').hide();
                }
                $detailsSecondPriceBtn.click(function (){
                    if (nextPriceContr){
                        nextPriceContr = false;
                        $detailsSecondPrice.show();
                        _SupplementNowNum = _SupplementNowNum + nextPrice;
                    } else {
                        nextPriceContr = true;
                        $detailsSecondPrice.hide();
                        _SupplementNowNum = _SupplementNowNum - nextPrice;
                    }
                    $totalPrice.text(currency + _SupplementNowNum);
                });
            }
            var $mainBox = $detailsForm.find('.main-box');
            $mainBox.each(function (){
                var _this = $(this);
                var $spans = _this.find('span');
                $spans.click(function (){
                    if (_product.is_group !== 1) {
                        if (_this.is('#colorBox')){
                            colorText = $(this).text();
                            if (!nextPriceContr) {
                                _SupplementNowNum = valueNumer * (nowPriceNum + _colorSupplement[colorText] + nextPrice);
                            } else {
                                _SupplementNowNum = valueNumer * (nowPriceNum + _colorSupplement[colorText]);
                            }
                            $totalPrice.text(currency + _SupplementNowNum);
                        }
                    }
                    $spans.removeClass('active');
                    $(this).addClass('active');
                });
            });
            $.init();
            // 增删减
            $('.details-form .main-box .number-box b:nth-of-type(1)').click(function (){
                valueNumer = $inputNum.val();
                valueNumer--;
                if (valueNumer <= 1){
                    valueNumer = 1;
                }
                $inputNum.val(valueNumer);
                if(nextPrice>0 && _product.is_group !== 1){
                    var ttp = _SupplementNowNum + (valueNumer-1) * nextPrice;
                    $totalPrice.text(currency + toThousands(ttp));
                }else{
                    if (_product.is_group == 1){
                        $totalPrice.text(currency + toThousands(valueNumer * groupPrice));
                    } else {
                        _SupplementNowNum = valueNumer * (nowPriceNum + _colorSupplement[colorText]);
                        $totalPrice.text(currency + toThousands(_SupplementNowNum));
                    }
                }
            });
            $('.details-form .main-box .number-box b:nth-of-type(2)').click(function (){
                valueNumer = $inputNum.val();
                valueNumer++;
                $inputNum.val(valueNumer);
                if(nextPrice>0 && _product.is_group !== 1){
                    var ttp = _SupplementNowNum + (valueNumer-1) * nextPrice;
                    $totalPrice.text(currency + toThousands(ttp));
                }else{
                    if (_product.is_group == 1){
                        $totalPrice.text(currency + toThousands(valueNumer * groupPrice));
                    } else {
                        _SupplementNowNum = valueNumer * (nowPriceNum + _colorSupplement[colorText]);
                        $totalPrice.text(currency + toThousands(_SupplementNowNum));
                    }
                }
            });
            // tab
            var $home = $('#home');
            var $content = $('#home .content');
            var $detatwoBanner = $('.detatwo-banner').outerHeight();
            var $detatwoPrice = $('.detatwo-price').outerHeight();
            var contentScrollTop = $content.scrollTop();
            var $detatwoBars = $('.detatwo-bars');
            var detatwoBarsHeight = $detatwoBars.outerHeight();
            var $detatwoContext = $('.detatwo-context');
            var detatwoBarsTop = $detatwoBars.offset().top + contentScrollTop;
            if (localStorageContr){
                localStorage.setItem('top', detatwoBarsTop);
                localStorage.setItem('contral', 'false');
            } else {
                var storageTop = localStorage.getItem('top');
            }
            if (storageTop){
                detatwoBarsTop = storageTop;
            }
            var detatwoContextTopArr = [];
            $content.scroll(function (){
                contentScrollTop = $(this).scrollTop();
                if (detatwoBarsTop <= contentScrollTop){
                    $detatwoBars.addClass('fixed');
                } else {
                    $detatwoBars.removeClass('fixed');
                }
            });
            $detatwoBars.find('li').each(function (index){
                $(this).click(function (){
                    var detatwoContextTop = $detatwoContext.eq(index).offset().top - detatwoBarsHeight + contentScrollTop;
                    $content.scrollTop(detatwoContextTop);
                });
            });
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
    // 跑马灯
    var $listBlockFirst = $('.list-li-marquee1');
    var $ul = $listBlockFirst.find('ul');
    var appendHtmlContr = true;
    if ($listBlockFirst.outerHeight() < $ul.outerHeight()){
        setLiMarquee($listBlockFirst);
    }
    function setLiMarquee(eleObj){
        var $liFirst = eleObj.find('li:first-child');
        var liFirstHeight = $liFirst.outerHeight();
        var ulTopNum = parseFloat($ul.css('top'));
        if (appendHtmlContr){
            appendHtmlContr = false;
            $ul.append('<li>' + $liFirst.html() + '</li>');
        }
        ulTopNum--;
        if (ulTopNum == -liFirstHeight){
            ulTopNum = 0;
            $liFirst.remove();
            appendHtmlContr = true;
        }
        $ul.css('top', ulTopNum);
        setTimeout(function (){
            setLiMarquee(eleObj);
        }, 30);
    }
    // footer-icon
    var popupTextArray = [
        {
            text: '<p>kingdomskymall The best online store, holding a serious attitude of traveling around the world looking for good products, and controlling the quality of product material, available underwear products, bags, shoes, kitchenware, sports etc, we strive to provide you excellent products.</p><button type="button" class="button">Back</button>'
        },
        {
            text: '<p>The user effect of this product depends on the personal condition, we can not guarantee every customer can achieve user effect like its advertisement. If you have any questions please contact the online service or send an email to (<a href="supportid@kingdomskymall.com" style="color:#F8770E"> supportid@kingdomskymall.com </a>), our company has the last interpretation power.</p><br><button type="button" class="button">Back</button>'
        },
        {
            text: '<p>After successful ordering, we will quickly process orders in the order, preparation time is about 3 working days, delivery time is usually about 7 working days.</p><br><button type="button" class="button">Back</button>'
        },
        {
            text: '<p>24 Hour Online Chat Service: <br> Eamil: <a href="mailto:supportid@kingdomskymall.com" style="color:#F8770E"> supportid@kingdomskymall.com </a> <br> If you have any questions, please contact the customer service staff, thank you for the cooperation.</p><br><button type="button" class="button">Back</button>'
        },
        {
            text: '<p> How to redeem and return products: </ p> <p> &nbsp; &nbsp; &nbsp; &nbsp; 1.If for personal reasons to apply for a refund: within 7 days of receipt, send an email to our selling practice center <a href="mailto:supportid@kingdomskymall.com" style="color:#F8770E"> supportid@kingdomskymall.com </a>, our customer service staff will handle your request within 1-3 days, and product cost wholly borne by consumers. </ p> <p> &nbsp; &nbsp; &nbsp; &nbsp; 2.If due to product quality issue to apply for return: within 7 days after receipt of goods, send email to our selling practice center <a href = "mailto: supportid@kingdomskymall.com" style = "color: # F8770E"> supportid@kingdomskymall.com </a>, our customer service staff will handle your request within 1-3 days, and the cost will be borne by the parties us. </ p> <p> Product redemption or refund procedure: </ p> <p> &nbsp; &nbsp; & nbs p; &nbsp; Receipt confirmation ---- Applying for a refund --- Customer service staff approves ---- Consumer resends the goods ----- Warehouse we receive and check --- Audit onsite or refund ---- -change or return the product, please note: order number, full name, mobile number. </ P><br><button type="button" class="button">Back</button>'
        },
        {
            text: 'Check<br><input><br><button type="button" class="button">Back</button>'
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
        if (groupContr){
            var $sizeActive = $('.sizeBox span.active');
            var $colorActive = $('.colorBox span.active');
            for (var j = 0; j < $sizeActive.length; j++){
                sizeArr.push($('.sizeBox span.active').eq(j).text());
            }
            for (var k = 0; k < $colorActive.length; k++){
                colorArr.push($('.colorBox span.active').eq(k).text());
            }
        } else {
            sizeArr.push($('#sizeBox span.active').text());
            colorArr.push($('#colorBox span.active').text());
            if (!nextPriceContr){
                sizeArr.push($('#secondSizeBox span.active').text());
                colorArr.push($('#secondColorBox span.active').text());
            }
        }
        var num = $('input[name=number]').val();
        var name = $('input[name=inputName]').val();
        var mobile = $('input[name=mobile]').val();
        var email = $('input[name=email]').val();
        var province = $('#province').val();
        var city = $('#city_select').val();
        var area = $('#area_val').val();
        var address = $('input[name=address]').val();
        var postCode = $('input[name=postCode]').val();
        var comment = $('textarea[name=comment]').val();
        var totalPriceNum = $('#totalPrice').text().replace(/[^0-9]/ig, '');
        $.ajax({
            type: 'post',
            url: '/shop/add/order',
            data: {
                host: requestId,
                is_group: isGroup,
                group_id: groupId,
                color: colorArr,
                size: sizeArr,
                num: num,
                name: name,
                mobile: mobile,
                email: email,
                province: province,
                city: city,
                area: area,
                address: address,
                post_code: postCode,
                comment: comment,
                total_price: totalPriceNum,
                propertyInfo: JSON.stringify(propertyInfo)
            },
            dataType: 'json',
            success: function (res){
                fbq('track', 'Purchase');
                $("#order_id").html('JTNX' + res.orderId);
                $("#order_price").html(res.total);
                $.popup('.popup-success');
            }
        });   
    });
});