function getCity(country, obj) {
    $.getJSON("/country/get-city", {'country':country, 'province': $(obj).val()}, function (data) {
        $("#city_select option").remove();
        $("#postCode").val("");
        $("#city_select").append("<option value=''>kota/daerah istimewah\n</option>");
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
        $("#area_select").append("<option value=''>daerah</option>");
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
    var result = [ ], counter = 0;
    num = (num || 0).toString().split('');
    for (var i = num.length - 1; i >= 0; i--) {
        counter++;
        result.unshift(num[i]);
        if (!(counter % 3) && i != 0) { result.unshift('.'); }
    }
    return result.join('');
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
    var priceHtml = '';
    // var requestId = 'shoes';   // 开发用，后续删除
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
    $.ajax({
        type: 'get',
        url: '/shop/api/' + requestId,
        dataType: 'json',
        success: function (res){
            var _product = res.product;
            var _images = _product.images;
            // 差价
            var _colorSupplement = res.colorSupplement; 
            var colorText = '';
            //banner
            for (var i = 0; i < _images.length; i++){
                var $swiperSlide = '<div class="swiper-slide"><img src="' + _images[i] + '"></div>';
                $('#swiperWrapper').append($swiperSlide);
            }
            $('.swiper-container').swiper({
                autoplay: 2000,
                autoplayDisableOnInteraction : false,
            });
            $('.detail-title h1').text(_product.sale_info + " " + _product.title);
            // price
            var salePrice = toThousands(_product.sale_price);
            priceHtml = '<del>' + currency + toThousands(_product.price) + '</del>\
                        <span class="discount gree">พิเศษ 39%</span>\
                        <div>\
                            <span>' + currency + '<span id="nowPrice">' + salePrice + '</span></span>\
                        </div>';
            $('.detail .price').html(priceHtml);
            //info
            $('#info').html(_product.info);
            //图片加载失败，添加默认图片
            $('#swiperWrapper img, #info img').error(function (){
                $(this).attr('src', '/themes/ID_3/images/details_banner1.jpg')
            });
            var nowPriceNum = Number($('#nowPrice').text().replace(/[^0-9\.]/ig, ''));
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
                        var colorSpans = '<span class="active color-image"><img src="' + _color[i].image + '">' + _color[i].name + '</span>';
                    } else {
                        var colorSpans = '<span class="color-image"><img src="' + _color[i].image + '">' + _color[i].name + '</span>';
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
        }
    });
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
            text: '<p>kingdomskymall Toko online terbaik,  berpegang sikap serius untuk berkeliling di seluruh dunia mencari produk yg baik, dan mengendalikan kualitas material produk, tersedia produk pakaian, tas, sepatu, dapur,olahraga dll, kami berupaya untuk menyediakan anda produk unggulan.</p><button type="button" class="button">Back</button>'
        },
        {
            text: '<p>Efek pengguna produk ini tergantung pada kondisi pribadi, kami tidak jamin setiap pelanggan bisa capai efek pengguna seperti iklannya. Jika ada pertanyaan silakan hubungi layan online atau kirim email ke ( <a href="supportid@kingdomskymall.com" style="color:#F8770E">supportid@kingdomskymall.com</a> ), perusahaan kami memiliki kekuatan interpretasi terakhir.</p><br><button type="button" class="button">Back</button>'
        },
        {
            text: '<p>Setelah sukses memesan, kami akan cepat memproses pesanan dengan urutannya, waktu persiapan sekitar 3 hari kerja, waktu pengiriman biasanya sekitar 7 hari kerja.</p><br><button type="button" class="button">Back</button>'
        },
        {
            text: '<p>Layanan Online Chat 24 Jam ：<br> Eamil：<a href="mailto:supportid@kingdomskymall.com" style="color:#F8770E">supportid@kingdomskymall.com</a><br>Jika ada pertanyaan apapun, silakan hubungi staf layanan pelanggan, terima kasih atas kerjasamanya.</p><br><button type="button" class="button">Back</button>'
        },
        {
            text: '<p>Cara menukar dan mengembali produk：</p><p>&nbsp;&nbsp;&nbsp;&nbsp;1.Jika karena alasan pribadi untuk mengajukan permohonan pengembalian: dalam 7 hari setelah tanda terima barang, kirim email ke pusat purja jual kami <a href="mailto:supportid@kingdomskymall.com" style="color:#F8770E">supportid@kingdomskymall.com</a>，staf layanan pelanggan kami akan menangani permintaan Anda dalam 1-3 hari, dan biaya ongkir produk sepenuhnya ditanggung oleh konsumen.</p> <p>&nbsp;&nbsp;&nbsp;&nbsp;2.Jika karena masalah kualitas produk untuk mengajukan permohonan pengembalian: dalam 7 hari setelah tanda terima barang, kirim email ke pusat purja jual kami <a href="mailto:supportid@kingdomskymall.com" style="color:#F8770E">supportid@kingdomskymall.com</a>， staf layanan pelanggan kami akan menangani permintaan Anda dalam 1-3 hari, dan biaya ongkir akan ditanggung oleh pihak kami.</p>                       <p>Prosedur penukaran atau pengembalian produk：</p>                       <p>&nbsp;&nbsp;&nbsp;&nbsp;Konfirmasi terima barang ----Mengajukan permohonan pengembalian---Staf layanan pelanggan menyetujui----Konsumen kirim kembali barangnya-----Gudang kami terima dan periksa---Audit penukaran atau pengembalian-----Penukaran atau pengembalian produk, silakan cacatan : nomor pesanan, nama lengkap, nomor HP.</p><br><button type="button" class="button">Back</button>'
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
    // var $mainBox = $detailsForm.find('.main-box');
    // $mainBox.each(function (){
    //     var $spans = $(this).find('span');
    //     $spans.click(function (){
    //         $spans.removeClass('active');
    //         $(this).addClass('active');
    //     });
    // });
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