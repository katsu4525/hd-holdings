<?php

// require_once(__DIR__ . '/menu_setting.php');
require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/temple_menu.php");

/*************************************************************
    管理画面 表示共通

    2017-05-31

    ログイン前と後で、画面デザインを分けた

 *************************************************************/

// 使い方　　

// $list = まんなか中身を表示させる関数();

// read_template($css,$js,$script,$list);


/*-------------------------------------------------------
    ログイン後のテンプレート
-------------------------------------------------------*/
function read_template($css, $js, $script, $list)
{
    global $VERSION;
    global $add_var;
    global $DF_COPYRIGHT;
    global $left_nav;
    global $DF_MENU;
    global $DF_COLOR_DARK;
    global $DF_COLOR_LIGHT;
    // $usermenu = getMenu($_SESSION['logkey']);

    // 管理者用
    $superadmin = "";
    // if ($_SESSION['auth'] == 1) {
    //     $superadmin = " style=\"background-color:red;\"";
    // }
    $last_time = (isset($_SESSION["lasttime"])) ? $_SESSION["lasttime"] : "";

    if ((int)$DF_MENU === 1){
      $menu_style = '';
      $open_css = '';
      $mask_elem = '<div id="mask"></div>';
      $leftnav_js = '<script src="./js/leftnav.js"></script>';
    } else {
      $menu_style = 'open';
      $open_css = '<link rel="stylesheet" href="./css/open.css">';
      $mask_elem = '';
      $leftnav_js = '<script src="./js/leftnav_open.js"></script>';
    }

    $str = <<< "HTML"
      <!DOCTYPE html>
      <html lang="ja">
      <head>
        <meta charset="UTF-8" />
        <meta name="robots" content="noindex" />
        <title>管理サイト</title>
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Cache-Control" content="no-store">
        <meta http-equiv="Expires" content="0">
        <!--ファビコン-->
        <link rel="icon" type="image/png" href="../img/favicon.png">
        <!-- <link rel="icon" href="favicon.ico" /> -->
        <link rel="stylesheet" href="./css/reset.css">
        <link rel="stylesheet" href="./css/base.css">
        {$open_css}
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/redmond/jquery-ui.css">
        <!-- <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css"> -->
        <style>
        .ui-widget-content {
            border: none;
        }
        header figure img {
          margin-top: 5px;
        }
        </style>
        <style>
          #info {
            background-color: $DF_COLOR_DARK;
          }
          #leftNav {
            background-color: $DF_COLOR_DARK;
          }
          #leftNav h3 {
            background-color: $DF_COLOR_DARK;
          }
          nav {
            background-color: $DF_COLOR_DARK;
          }
          .toggle_btn {
            background-color: $DF_COLOR_DARK;
          }
          #leftNav li a {
            color: $DF_COLOR_LIGHT;
          }
          #mainArea h2 {
            color: $DF_COLOR_LIGHT;
          }
          #mainArea table th {
            background-color: $DF_COLOR_LIGHT;
          }
          #mainArea dt {
            color: $DF_COLOR_LIGHT;
            border-left: 3px solid $DF_COLOR_LIGHT;
          }
          #mainArea dt .bottomScroll {
            border-bottom: 1px solid $DF_COLOR_LIGHT;
            font-size: 90%;
          }
          #image-btn {
            background: $DF_COLOR_LIGHT;
            box-shadow: 0 3px 0 $DF_COLOR_DARK;
          }
          #image-btn:hover {
            box-shadow: 0 3px 0 $DF_COLOR_DARK;
          }
          .submit:hover {
            color: $DF_COLOR_LIGHT;
          }
          dd .detail .title {
            background-color: $DF_COLOR_LIGHT;
          }
          .pankuzu a {
            color: $DF_COLOR_LIGHT;
            text-decoration: 1px underline $DF_COLOR_LIGHT;
          }
          header .logout a {
            color:  $DF_COLOR_LIGHT;
          }
          #mainArea table .link {
            background-color: $DF_COLOR_LIGHT;
            border: 1.5px solid $DF_COLOR_LIGHT;
            box-shadow: 0 2px 0 $DF_COLOR_DARK;
          }
          #mainArea table .link:hover {
            color: $DF_COLOR_LIGHT;
            background-color: #FFF !important;
            box-shadow: none !important;
          }
        </style>
        {$css}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="./js/jquery.cookie.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="./js/jquery.validate.min.js"></script>
        <script src="./js/additional-methods.min.js"></script>
        <script src="./js/messages_ja.js"></script>
        <script src="./js/menu.js"></script>
        {$leftnav_js}
        {$js}

        <script>
          $(window).on('load', function() {
            var _window = $(window),
            _header = $('#leftNav'),
            heroBottom;

            _window.on('scroll',function(){     
              heroBottom = 126;
              if(_window.scrollTop() > heroBottom){
                _header.addClass('kotei');   
              }
              else{
                _header.removeClass('kotei');   
              }
            });

            _window.trigger('scroll'); 
          });
        </script>

        <script language="JavaScript">
          function del_chk(url){
            if (confirm("本当に削除しますか？") ){
              location.href=url;
            }
          }
          // loadingを表示
          function loading(){
            // const validResult = $('form').valid();
            // if (!validResult){
            //   return false;
            // }
            $('.loader').css('display', 'block');
            $('.filter').css('display', 'block');
          }
          // confirmを表示
          function confdialog(message){
            return new Promise((resolve) => {

              $('#cfdialog').remove();
  
              const confirmDialog = `
                <div id="cfdialog">
                  <div class="ui-widget">
                    <div class="ui-state-highlight ui-corner-all" style="padding: 1em;">
                      <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                          \${message}
                      </p>
                    </div>
                  </div>
                </div> 
              `;
  
              $('body').append(confirmDialog);
  
              $( "#cfdialog" ).dialog({
                resizable: false,
                height: "auto",
                width: 500,
                modal: true,
                open: function() { // Xボタン非表示
                        $(".ui-dialog-titlebar-close", $(this).closest(".ui-dialog")).hide();
                      },
                buttons: {
                  "OK": function() {
                    $( this ).dialog( "close" );
                    resolve(true);
                  },
                  "キャンセル": function() {
                    $( this ).dialog( "close" );
                    resolve(false);
                  }
                }
              });
            });
          }
          // aleartを表示
          function alertdialog(message){
            return new Promise((resolve) => {

              $('#aldialog').remove();
  
              const alertDialog = `
                <div id="aldialog">
                  <div class="ui-widget">
                    <div class="ui-state-highlight ui-corner-all" style="padding: 1em;">
                      <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                          \${message}
                      </p>
                    </div>
                  </div>
                </div> 
              `;
  
              $('body').append(alertDialog);
  
              $( "#aldialog" ).dialog({
                resizable: false,
                height: "auto",
                width: 500,
                modal: true,
                open: function() { // Xボタン非表示
                  $(".ui-dialog-titlebar-close", $(this).closest(".ui-dialog")).hide();
                },
                buttons: {
                  "OK": function() {
                    $( this ).dialog( "close" );
                    resolve(true);
                  },
                }
              });
            });
          }
        </script>
        <script>
          $(function(){
            for (var i = 1; i <= $('ul').length; i++) {
              // クッキーがblockであれば読み込み時にメニューをオープンする
              if ($.cookie('child' + i) == 'block') {
                $('#child' + i).show();
              }
            }
            $('h3').click(function() {
              var child = $(this).next('ul');
              // メニュー表示/非表示
              $(child).slideToggle('fast', function() {
                // 有効期限は1日（クッキーにはドメインをセットしない、ブラウザを閉じたら初期化）
                $.cookie($(child).attr('id'), $(child).css('display'), { expires: 1 });
              });
            });
            //ソータブル
            $('#leftNav').sortable({
              cursor: "move",
              opacity: 0.7,
              items: '.sort_block',
            });
            $('#leftNav').disableSelection();
            $("#leftNav").sortable( {
                update: function(event,ui) {
                var updateArray = $("#leftNav").sortable("toArray").join(",");
                $.cookie("sortable", updateArray, {expires: 100});
              }
            });

            if($.cookie("sortable")) {
              var cookieValue = $.cookie("sortable").split(",");
              // console.log(cookieValue);
              $.each(cookieValue, function(index, value){
                $("#" + value).appendTo("#leftNav");
              });
            }
            
            // ページ独自JS
            {$script}
          });
        </script>
      </head>
      <body class="{$menu_style}">
        <div id="wrap">
          <div id="container" class="clearfix">
            <div class="fixed">
              <header>
                <h1 style="display: inline-block;"><a href="first.php"><figure><img src="./img/logo.png" alt="ロゴ"></figure></a></h1>
                <section class="logout"><a href="logout.php"><img src="img/logout.svg">ログアウト</a></section>
              </header>
              <ul id="info" {$superadmin}>
                <!-- <li>
                    <figure><img src="img/person.svg" alt=""></figure>
                </li> -->
                <li>
                  <figure><img src="img/clock.svg" alt=""></figure>
                  <p>前回ログイン : {$last_time}</p>
                </li>
                <!-- <li class="manual">
                  <a href="./manual/" target="_blank" rel="noopener noreferrer">
                    <figure><img src="img/book.svg" alt="本のアイコン" width="24" height="24"></figure>
                    操作マニュアル
                  </a>
                </li> -->
              </ul>
            </div>
            <section id="mainArea">
              <section id="mainContents">
                {$list}
              </section>
            </section>
            <div id="burger"></div>
            <!-- <ul id="leftNav">
                <h2><a href="first.php" style="text-decoration:none;">メニュー</a></h2>
                <li class="sort_block" id="item-1">
                    <h3>ダッシュボード</h3>
                    <ul id="child1">
                        <li><a href="" id="menu0">メニュー１</a></li>
                        <li><a href="" id="menu1">メニュー２</a></li>
                        <li><a href="" id="menu2">メニュー３</a></li>
                    </ul>
                </li>
            </ul> -->
            {$left_nav}
            {$mask_elem}
          </div>
        </div>
        <div id="footer">{$DF_COPYRIGHT}</div>
        <!-- <div class="loader"></div> -->
        <div class="loader">
          <h1>しばらくお待ちください...</h1>
          <span></span>
          <span></span>
          <span></span>
        </div>
        <div class="filter"></div>
      </body>
      <!-- InstanceEnd --></html>
      HTML;

  echo $str;
}
