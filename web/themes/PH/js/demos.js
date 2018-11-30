$(function () {
  'use strict';

  //涓嬫媺鍒锋柊椤甸潰
  $(document).on("pageInit", "#page-ptr", function(e, id, page) {
    var $content = $(page).find(".content").on('refresh', function(e) {
      // 妯℃嫙2s鐨勫姞杞借繃绋�
      setTimeout(function() {
        var cardHTML = '<div class="card">' +
          '<div class="card-header">鏍囬</div>' +
          '<div class="card-content">' +
          '<div class="card-content-inner">鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭鍐呭' +
          '</div>' +
          '</div>' +
          '</div>';

        $content.find('.card-container').prepend(cardHTML);
        // $(window).scrollTop(0);
        // 鍔犺浇瀹屾瘯闇€瑕侀噸缃�
        $.pullToRefreshDone($content);
      }, 2000);
    });
  });

  //鏃犻檺婊氬姩
  $(document).on("pageInit", "#page-infinite-scroll-bottom", function(e, id, page) {
    var loading = false;
    // 姣忔鍔犺浇娣诲姞澶氬皯鏉＄洰
    var itemsPerLoad = 20;
    // 鏈€澶氬彲鍔犺浇鐨勬潯鐩�
    var maxItems = 100;
    var lastIndex = $('.list-container li').length;
    function addItems(number, lastIndex) {
      // 鐢熸垚鏂版潯鐩殑HTML
      var html = '';
      for (var i = lastIndex + 1; i <= lastIndex + number; i++) {
        html += '<li class="item-content"><div class="item-inner"><div class="item-title">鏂版潯鐩�</div></div></li>';
      }
      // 娣诲姞鏂版潯鐩�
      $('.infinite-scroll .list-container').append(html);
    }
    $(page).on('infinite', function() {
      // 濡傛灉姝ｅ湪鍔犺浇锛屽垯閫€鍑�
      if (loading) return;
      // 璁剧疆flag
      loading = true;
      // 妯℃嫙1s鐨勫姞杞借繃绋�
      setTimeout(function() {
        // 閲嶇疆鍔犺浇flag
        loading = false;
        if (lastIndex >= maxItems) {
          // 鍔犺浇瀹屾瘯锛屽垯娉ㄩ攢鏃犻檺鍔犺浇浜嬩欢锛屼互闃蹭笉蹇呰鐨勫姞杞�
          $.detachInfiniteScroll($('.infinite-scroll'));
          // 鍒犻櫎鍔犺浇鎻愮ず绗�
          $('.infinite-scroll-preloader').remove();
          return;
        }
        addItems(itemsPerLoad,lastIndex);
        // 鏇存柊鏈€鍚庡姞杞界殑搴忓彿
        lastIndex = $('.list-container li').length;
        $.refreshScroller();
      }, 1000);
    });
  });

  //椤堕儴鏃犻檺婊氬姩
  $(document).on("pageInit", "#page-infinite-scroll-top", function(e, id, page) {
    function addItems(number, lastIndex) {
      // 鐢熸垚鏂版潯鐩殑HTML
      var html = '';
      for (var i = lastIndex+ number; i > lastIndex ; i--) {
        html += '<li class="item-content"><div class="item-inner"><div class="item-title">鏉＄洰'+i+'</div></div></li>';
      }
      // 娣诲姞鏂版潯鐩�
      $('.infinite-scroll .list-container').prepend(html);

    }
    var timer = false;
    $(page).on('infinite', function() {
       var lastIndex = $('.list-block li').length;
       var lastLi = $(".list-container li")[0];
       var scroller = $('.infinite-scroll-top');
       var scrollHeight = scroller[0].scrollHeight; // 鑾峰彇褰撳墠婊氬姩鍏冪礌鐨勯珮搴�
      // 濡傛灉姝ｅ湪鍔犺浇锛屽垯閫€鍑�
      if (timer) {
        clearTimeout(timer);
      }

      // 妯℃嫙1s鐨勫姞杞借繃绋�
      timer = setTimeout(function() {

        addItems(20,lastIndex);

        $.refreshScroller();
        //  lastLi.scrollIntoView({
        //     behavior: "smooth",
        //     block:    "start"
        // });
        // 灏嗘粴鍔ㄦ潯鐨勪綅缃缃负鏈€鏂版粴鍔ㄥ厓绱犻珮搴﹀拰涔嬪墠鐨勯珮搴﹀樊
        scroller.scrollTop(scroller[0].scrollHeight - scrollHeight);
      }, 1000);
    });

  });
  //test demo js

  //澶氫釜鏍囩椤典笅鐨勬棤闄愭粴鍔�
  $(document).on("pageInit", "#page-fixed-tab-infinite-scroll", function(e, id, page) {
    var loading = false;
    // 姣忔鍔犺浇娣诲姞澶氬皯鏉＄洰
    var itemsPerLoad = 20;
    // 鏈€澶氬彲鍔犺浇鐨勬潯鐩�
    var maxItems = 100;
    var lastIndex = $('.list-container li')[0].length;
    function addItems(number, lastIndex) {
      // 鐢熸垚鏂版潯鐩殑HTML
      var html = '';
      for (var i = lastIndex + 1; i <= lastIndex + number; i++) {
        html += '<li class="item-content""><div class="item-inner"><div class="item-title">鏂版潯鐩�</div></div></li>';
      }
      // 娣诲姞鏂版潯鐩�
      $('.infinite-scroll.active .list-container').append(html);
    }
    $(page).on('infinite', function() {
      // 濡傛灉姝ｅ湪鍔犺浇锛屽垯閫€鍑�
      if (loading) return;
      // 璁剧疆flag
      loading = true;
      var tabIndex = 0;
      if($(this).find('.infinite-scroll.active').attr('id') == "tab2"){
        tabIndex = 0;
      }
      if($(this).find('.infinite-scroll.active').attr('id') == "tab3"){
        tabIndex = 1;
      }
      lastIndex = $('.list-container').eq(tabIndex).find('li').length;
      // 妯℃嫙1s鐨勫姞杞借繃绋�
      setTimeout(function() {
        // 閲嶇疆鍔犺浇flag
        loading = false;
        if (lastIndex >= maxItems) {
          // 鍔犺浇瀹屾瘯锛屽垯娉ㄩ攢鏃犻檺鍔犺浇浜嬩欢锛屼互闃蹭笉蹇呰鐨勫姞杞�
          //$.detachInfiniteScroll($('.infinite-scroll').eq(tabIndex));
          // 鍒犻櫎鍔犺浇鎻愮ず绗�
          $('.infinite-scroll-preloader').eq(tabIndex).hide();
          return;
        }
        addItems(itemsPerLoad,lastIndex);
        // 鏇存柊鏈€鍚庡姞杞界殑搴忓彿
        lastIndex =  $('.list-container').eq(tabIndex).find('li').length;
        $.refreshScroller();
      }, 1000);
    });
  });

  //鍥剧墖娴忚鍣�
  $(document).on("pageInit", "#page-photo-browser", function(e, id, page) {
    var myPhotoBrowserStandalone = $.photoBrowser({
      photos : [
        '//img.alicdn.com/tps/i3/TB1kt4wHVXXXXb_XVXX0HY8HXXX-1024-1024.jpeg',
        '//img.alicdn.com/tps/i1/TB1SKhUHVXXXXb7XXXX0HY8HXXX-1024-1024.jpeg',
        '//img.alicdn.com/tps/i4/TB1AdxNHVXXXXasXpXX0HY8HXXX-1024-1024.jpeg',
      ]
    });
    //鐐瑰嚮鏃舵墦寮€鍥剧墖娴忚鍣�
    $(page).on('click','.pb-standalone',function () {
      myPhotoBrowserStandalone.open();
    });
    /*=== Popup ===*/
    var myPhotoBrowserPopup = $.photoBrowser({
      photos : [
        '//img.alicdn.com/tps/i3/TB1kt4wHVXXXXb_XVXX0HY8HXXX-1024-1024.jpeg',
        '//img.alicdn.com/tps/i1/TB1SKhUHVXXXXb7XXXX0HY8HXXX-1024-1024.jpeg',
        '//img.alicdn.com/tps/i4/TB1AdxNHVXXXXasXpXX0HY8HXXX-1024-1024.jpeg',
      ],
      type: 'popup'
    });
    $(page).on('click','.pb-popup',function () {
      myPhotoBrowserPopup.open();
    });
    /*=== 鏈夋爣棰� ===*/
    var myPhotoBrowserCaptions = $.photoBrowser({
      photos : [
        {
          url: '//img.alicdn.com/tps/i3/TB1kt4wHVXXXXb_XVXX0HY8HXXX-1024-1024.jpeg',
          caption: 'Caption 1 Text'
        },
        {
          url: '//img.alicdn.com/tps/i1/TB1SKhUHVXXXXb7XXXX0HY8HXXX-1024-1024.jpeg',
          caption: 'Second Caption Text'
        },
        // 杩欎釜娌℃湁鏍囬
        {
          url: '//img.alicdn.com/tps/i4/TB1AdxNHVXXXXasXpXX0HY8HXXX-1024-1024.jpeg',
        },
      ],
      theme: 'dark',
      type: 'standalone'
    });
    $(page).on('click','.pb-standalone-captions',function () {
      myPhotoBrowserCaptions.open();
    });
  });


  //瀵硅瘽妗�
  $(document).on("pageInit", "#page-modal", function(e, id, page) {
    var $content = $(page).find('.content');
    $content.on('click','.alert-text',function () {
      $.alert('杩欐槸涓€娈垫彁绀烘秷鎭�');
    });

    $content.on('click','.alert-text-title', function () {
      $.alert('杩欐槸涓€娈垫彁绀烘秷鎭�', '杩欐槸鑷畾涔夌殑鏍囬!');
    });

    $content.on('click', '.alert-text-title-callback',function () {
      $.alert('杩欐槸鑷畾涔夌殑鏂囨', '杩欐槸鑷畾涔夌殑鏍囬!', function () {
        $.alert('浣犵偣鍑讳簡纭畾鎸夐挳!')
      });
    });
    $content.on('click','.confirm-ok', function () {
      $.confirm('浣犵‘瀹氬悧?', function () {
        $.alert('浣犵偣鍑讳簡纭畾鎸夐挳!');
      });
    });
    $content.on('click','.prompt-ok', function () {
      $.prompt('浣犲彨浠€涔堥棶棰�?', function (value) {
        $.alert('浣犺緭鍏ョ殑鍚嶅瓧鏄�"' + value + '"');
      });
    });
  });

  //鎿嶄綔琛�
  $(document).on("pageInit", "#page-action", function(e, id, page) {
    $(page).on('click','.create-actions', function () {
      var buttons1 = [
        {
          text: '璇烽€夋嫨',
          label: true
        },
        {
          text: '鍗栧嚭',
          bold: true,
          color: 'danger',
          onClick: function() {
            $.alert("浣犻€夋嫨浜嗏€滃崠鍑衡€�");
          }
        },
        {
          text: '涔板叆',
          onClick: function() {
            $.alert("浣犻€夋嫨浜嗏€滀拱鍏モ€�");
          }
        }
      ];
      var buttons2 = [
        {
          text: '鍙栨秷',
          bg: 'danger'
        }
      ];
      var groups = [buttons1, buttons2];
      $.actions(groups);
    });
  });

  //鍔犺浇鎻愮ず绗�
  $(document).on("pageInit", "#page-preloader", function(e, id, page) {
    $(page).on('click','.open-preloader-title', function () {
      $.showPreloader('鍔犺浇涓�...')
      setTimeout(function () {
        $.hidePreloader();
      }, 2000);
    });
    $(page).on('click','.open-indicator', function () {
      $.showIndicator();
      setTimeout(function () {
        $.hideIndicator();
      }, 2000);
    });
  });


  //閫夋嫨棰滆壊涓婚
  $(document).on("click", ".select-color", function(e) {
    var b = $(e.target);
    document.body.className = "theme-" + (b.data("color") || "");
    b.parent().find(".active").removeClass("active");
    b.addClass("active");
  });

  //picker
  $(document).on("pageInit", "#page-picker", function(e, id, page) {
    $("#picker").picker({
      toolbarTemplate: '<header class="bar bar-nav">\
        <button class="button button-link pull-left">\
      鎸夐挳\
      </button>\
      <button class="button button-link pull-right close-picker">\
      纭畾\
      </button>\
      <h1 class="title">鏍囬</h1>\
      </header>',
      cols: [
        {
          textAlign: 'center',
          values: ['iPhone 4', 'iPhone 4S', 'iPhone 5', 'iPhone 5S', 'iPhone 6', 'iPhone 6 Plus', 'iPad 2', 'iPad Retina', 'iPad Air', 'iPad mini', 'iPad mini 2', 'iPad mini 3'],
          cssClass: 'picker-items-col-normal'
        }
      ]
    });
    $("#picker-name").picker({
      toolbarTemplate: '<header class="bar bar-nav">\
      <button class="button button-link pull-right close-picker">纭畾</button>\
      <h1 class="title">璇烽€夋嫨绉板懠</h1>\
      </header>',
      cols: [
        {
          textAlign: 'center',
          values: ['璧�', '閽�', '瀛�', '鏉�', '鍛�', '鍚�', '閮�', '鐜�']
        },
        {
          textAlign: 'center',
          values: ['鏉颁鸡', '纾�', '鏄�', '灏忛箯', '鐕曞Э', '鑿茶彶', 'Baby']
        },
        {
          textAlign: 'center',
          values: ['鍏堢敓', '灏忓']
        }
      ]
    });
  });
  $(document).on("pageInit", "#page-datetime-picker", function(e) {
    $("#datetime-picker").datetimePicker({
      toolbarTemplate: '<header class="bar bar-nav">\
      <button class="button button-link pull-right close-picker">纭畾</button>\
      <h1 class="title">閫夋嫨鏃ユ湡鍜屾椂闂�</h1>\
      </header>'
    });
  });

  $(document).on("pageInit", "#page-city-picker", function(e) {
    $("#city-picker").cityPicker({
        value: ['澶╂触', '娌充笢鍖�']
        //value: ['鍥涘窛', '鍐呮睙', '涓滃叴鍖�']
    });
  });

  $.init();
});