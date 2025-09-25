<?php

/**
 * @file news-list.php
 * @brief お知らせ一覧ページ(HoriTech)
 * @date 2025-09-18
 *
 * Copyright isis Co.,ltd.
 */
use Stlib\Stlib\Stlib;

require_once(__DIR__ . "/../admin/common/common_setting.php");
require_once(__DIR__ . "/../admin/lib/Stlib.php");
require_once(__DIR__ . '/../admin/lib/Pager.php');

$stlib = new Stlib();
$news_list = '';

$select_cate = filter_input(INPUT_GET, 'ca');
if (empty($select_cate)) {
  $select_cate = 'all';
}

switch ($select_cate) {
  // case 'holdings':
  //   $select_cate = 'HRホールディングス';
  //   break;
  // case 'horinet':
  //   $select_cate = '堀通信';
  //   break;
  case 'horitech':
    $select_cate = 'HoriTech';
    break;
  case 'other':
    $select_cate = 'その他';
    break;
  case 'all':
  default:
    $select_cate = 'すべて';
    break;
}

// 表示件数
define("MAX_ITEMS", 20);

try {
  $sql = "SELECT COUNT(*) FROM news_tb WHERE ne_is_public = 1";
  $bind = [];
  if ($select_cate !== 'すべて') {
    $sql .= " AND ne_cate = ?";
    $bind[] = $select_cate;
  } else {
    $sql .= " AND (ne_cate = 'HoriTech' OR ne_cate = 'その他')";
  }
  $sql .= " ORDER BY ne_created DESC";
  $cnt = $db->setSql($sql, $bind, PDO::FETCH_COLUMN, 'fetch');

  // ページャー
  if ($cnt > 0){
    if ($cnt > MAX_ITEMS){
      $perPage = MAX_ITEMS;
    } else {
      $perPage = $cnt;
    }

    $params = array(
      "mode" => 'Sliding', //リンク方式：ジャンプ型(Jumping)、スライド型(Sliding)
      "perPage" => $perPage, //1ページあたりの項目数
      "delta" => 1, //リンクに表示させるページ数
      "totalItems" => $cnt, //項目の総数(この場合マッチした総件数）
      "firstPagePre" => "",
      "firstPageText" => "先頭",
      "firstPagePost" => "",
      "prevImg" => "＜",
      "lastPagePre" => "",
      "lastPageText" => "最後",
      "lastPagePost" => "",
      "nextImg" => "＞",
      "separator" => "",
      "spacesAfterSeparator" => 0, //リンク表示の文字幅(デフォルトだと結構広い)
      "spacesBerorSeparator" => 0, //同じく
    );
    $pager = @Pager::factory($params); // Pagerの設定を有効化
    $navi = @$pager->getLinks(); //getLinkメソッドで作った各ページへのリンク情報を$naviへ格納。
    $start = ($pager->getCurrentPageID() - 1) * $perPage;
    $cnt = $perPage;

    $sql = "SELECT * FROM news_tb WHERE ne_is_public = 1";
    $bind = [];
    if ($select_cate !== 'すべて') {
      $sql .= " AND ne_cate = ?";
      $bind[] = $select_cate;
    } else {
      $sql .= " AND (ne_cate = 'HoriTech' OR ne_cate = 'その他')";
    }
    $sql .= " ORDER BY ne_created DESC LIMIT ?, ?";
    $bind[] = $start;
    $bind[] = $cnt;
    $news_data = $db->setSql($sql, $bind);
    $news_data = $stlib->xssEs($news_data);

    foreach ((array)$news_data as $val) {
      switch ($val['ne_cate']) {
        // case 'HRホールディングス':
        //   $cate = '<span class="bg-pl">HRホールディングス</span>';
        //   break;
        // case '堀通信':
        //   $cate = '<span class="bg-gr">堀通信</span>';
        //   break;
        case 'HoriTech':
          $cate = '<span class="bg-gr">HoriTech</span>';
          break;
        case 'その他':
          $cate = '<span class="bg-or">その他</span>';
          break;
        default:
          break;
      }

      // $title = (empty($val['ne_url'])) ? $val['ne_title'] : "<a href='{$val['ne_url']}'>{$val['ne_title']}<img src='images/link-icon.svg' alt=''/></a>";

      // $news_list .= <<< "HTML"
      // <div class="index-news-list">
      //   <p class="date">{$val['ne_created']}</p>
      //   <p class="cate">{$cate}</p>
      //   <p class="text">{$title}</p>
      // </div> 
      // HTML;

      $val['ne_content'] = nl2br($val['ne_content']);
      $content = (empty($val['ne_url'])) ? $val['ne_content'] : "<a href='{$val['ne_url']}' target='_blank' rel='noopener noreferrer'>{$val['ne_content']}<img src='images/link-icon.svg' alt=''/></a>";

      $news_list .= <<< "HTML"
        <div class="index-news-list">
          <p class="date">{$val['ne_created']}</p>
          <p class="cate">{$cate}</p>
          <p class="text">{$content}</p>
        </div> 
      HTML;
    }

    $navi['back'] = trim($navi['back'], '&nbsp;&nbsp;&nbsp;');
    $navi['pages'] = trim($navi['pages'], '&nbsp;&nbsp;&nbsp;');
    $navi['next'] = trim($navi['next'], '&nbsp;&nbsp;&nbsp;');
  } else {
    $navi['all'] = "";
    $navi['back'] = "";
    $navi['pages'] = "";
    $navi['next'] = "";
    $navi['first'] = "";
    $navi['last'] = "";
  }
} catch (Exception $e) {
  recordLog($e);
  header("Location: error.html");
  exit;
}

?>

<!doctype html>
<html><!-- InstanceBegin template="/Templates/temp.dwt" codeOutsideHTMLIsLocked="false" -->
<head prefix="og: https://ogp.me/ns#">
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KGHXKSVC');</script>
<!-- End Google Tag Manager -->
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>お知らせ一覧｜人と社会をつなぐ通信インフラの総合サポートと照明から消防設備まで電気工事をトータル対応 【株式会社 HoriTech】</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="descdata" -->
<meta name="description" content="お知らせ一覧のページです" />
<!-- InstanceEndEditable -->
<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
<meta name="format-detection" content="telephone=no">
<!-- InstanceBeginEditable name="head" -->
<meta property="og:url" content="https://www.horinet.co.jp/horitech/news-list.php" />
<meta property="og:type" content="article" />
<meta property="og:title" content="お知らせ一覧｜人と社会をつなぐ通信インフラの総合サポートと照明から消防設備まで電気工事をトータル対応 【株式会社 HoriTech】" />
<meta property="og:description" content="お知らせ一覧のページです" />
<meta property="og:site_name" content="株式会社 HoriTech" />
<meta property="og:image" content="https://www.horinet.co.jp/horitech/images/ogp.jpg" />
<!-- InstanceEndEditable -->
<!--CSS-->
<link href="css/reset.css" rel="stylesheet" type="text/css">
<link href="css/base.css" rel="stylesheet" type="text/css">
<link href="css/header.css" rel="stylesheet" type="text/css">
<link href="css/footer.css" rel="stylesheet" type="text/css">
<link href="css/nav.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet" type="text/css">
<!--googlefont-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap" rel="stylesheet">
<!--ファビコン-->
<link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KGHXKSVC"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!--ヘッドナビ-->
<header id="header">
  <div id="h-box">
    <h1><a href="index.php"><img src="images/h-logo.svg" width="271" height="53" alt="株式会社 HoriTech"/></a></h1>
    <div class="global-nav">
      <div class="global-nav-button">
        <div class="global-nav-button-icon"></div>
      </div>
      <div class="global-nav-item-list"> 
        <div class="global-nav-item"><a href="company.html">企業情報</a></div>
        <div class="global-nav-item"><a href="communication-service.html">通信工事部</a></div>
        <div class="global-nav-item"><a href="electrical-service.html">電気工事部</a></div>
        <div class="global-nav-item"><a href="works.html">導入事例</a></div>
        <div class="global-nav-item"><a href="qualification.html">保有資格</a></div>
        <div class="global-nav-item"><a href="https://www.horinet.co.jp/contact.php">お問い合わせ</a></div>
      </div>
    </div>
    <!-- .global-nav --> 
  </div>
</header>
<main> <!-- InstanceBeginEditable name="mainArea" -->
  <section class="title-area">
    <h2>お知らせ一覧</h2>
  </section>
  <div class="pan">
    <a href="./">HoriTech</a>
    <p class="current-page">お知らせ一覧</p>
  </div>
  <div id="news-cate-nav">
    <p><a href="news-list.php?ca=horitech" class="bg-gr">HoriTech</a></p>
    <p><a href="news-list.php?ca=other" class="bg-or">その他</a></p>
  </div>
    <section id="news-list" class="inner">
      <!-- <div class="index-news-list">
        <p class="date">2025.10.01</p>
        <p class="cate"><span class="bg-or">その他</span></p>
        <p class="text"><a href="#">ダミーテキスト：ホームページリニューアル</a></p>
      </div>
      <div class="index-news-list">
        <p class="date">2025.10.01</p>
        <p class="cate"><span class="bg-gr">HoriTech</span></p>
        <p class="text"><a href="#">ダミーテキスト：ホームページリニューアル</a></p>
      </div>
      <div class="index-news-list">
        <p class="date">2025.10.01</p>
        <p class="cate"><span class="bg-gr">HoriTech</span></p>
        <p class="text"><a href="#">ダミーテキスト：ホームページリニューアル</a></p>
      </div> -->
      <?= $news_list ?>
      <ol class="pagination">
        <!-- <li class="prev"><a href="#">＜</a></li>
        <li class="current"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li class="next"><a href="#">＞</a></li> -->
        <li class="prev"><?= $navi['back'] ?></li>
        <?= $navi['pages'] ?>
        <li class="next"><?= $navi['next'] ?></li>
      </ol>
    </section>
  <!-- InstanceEndEditable --> </main>
<!--フッター-->
<footer>
  <section class="f-contact">
    <div class="inner">
      <div class="logo-nav">
        <figure class="f-log"><a href="index.php"><img src="images/f-logo.svg" width="271" height="53" alt="株式会社 HoriTech"/></a></figure>
        <ul>
          <li class="f-nav"><a href="company.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>企業情報</a></li>
          <li class="f-nav"><a href="communication-service.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>通信工事部</a></li>
          <li class="f-nav"><a href="electrical-service.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>電気工事部</a></li>
          <li class="f-nav"><a href="works.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>導入事例</a></li>
          <li class="f-nav"><a href="qualification.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>保有資格</a></li>
          <li class="f-pv"><a href="https://www.horinet.co.jp/privacy.html" target="_blank">プライバシーポリシー</a></li>
        </ul>
      </div>
      <div class="tel-form">
        <p class="f-form"><a href="https://www.horinet.co.jp/contact.php">お問い合わせ</a></p>
        <figure class="f-tel"><a href="tel:0773-22-1120"><img src="images/tel1.svg" alt="通信工事部電話番号"/></a></figure>
      </div>
    </div>
    <div class="bnr-area">
      <p>グループ<br class="spOnly">会社</p>
      <div>
        <figure><a href="https://www.horinet.co.jp/" target="_blank"><img src="images/group-1.svg" width="560" height="160" alt="HRホールディングス"/></a></figure>
        <figure><a href="https://www.horinet.co.jp/horitech" target="_blank"><img src="images/group-2.svg" width="560" height="160" alt="株式会社堀通信"/></a></figure>
      </div>
    </div>
  </section>
  <p class="copy-r text-center">Copyright &copy; HoriTech All Rights Reserved.</p>
</footer>
<!-- jQuery --> 
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script> 
<!-- js --> 
<script>
  jQuery(function($){
    // ハンバーガーボタン
    $(document).on('click', '.global-nav-button', function(e){
      const $t = $(e.currentTarget);
      $t.toggleClass('open');
      $t.closest('.global-nav').toggleClass('open');
    });

    // サブメニュー開閉
    $(document).on('click', '.global-nav-item > a', function(e){
      const $t = $(e.currentTarget);
      const $next = $t.next('.global-nav-sub-item-list');
      if ($next.length > 0) {
        e.preventDefault();
        $t.toggleClass('open');
        $next.toggleClass('open');
      }
    });
  });
</script>
<!-- InstanceBeginEditable name="foot" -->
<style>
  #news-list .pagination span {
    border-bottom: 2px solid #008db7;
    padding: 0 5px;
  }
</style>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>