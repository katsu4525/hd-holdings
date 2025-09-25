<?php

/**
 * @file index.php
 * @brief ホールディングスTOPページ
 * @date 2025-09-18
 *
 * Copyright isis Co.,ltd.
 */

use Stlib\Stlib\Stlib;

require_once(__DIR__ . "/admin/common/common_setting.php");
require_once(__DIR__ . "/admin/lib/Stlib.php");

$stlib = new Stlib();
$news_list = '';

try {
  $sql = "SELECT * FROM news_tb WHERE ne_is_public = 1 ORDER BY ne_created DESC LIMIT 3";
  $news_data = $db->setSql($sql);
  $news_data = $stlib->xssEs($news_data);

  foreach ((array)$news_data as $val) {
    switch ($val['ne_cate']) {
      case 'HRホールディングス':
        $cate = '<span class="bg-pl">HRホールディングス</span>';
        break;
      case '堀通信':
        $cate = '<span class="bg-bl">堀通信</span>';
        break;
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
} catch (Exception $e) {
  recordLog($e);
  header("Location: error.html");
  exit;
}

?>

<!doctype html>
<html><!-- InstanceBegin template="/Templates/tempHD.dwt" codeOutsideHTMLIsLocked="false" -->
<head prefix="og: https://ogp.me/ns#">
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MMT98P5K');</script>
<!-- End Google Tag Manager -->
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>京都・福知山の電気・通信・ITインフラ構築｜【HRホールディングス】</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="descdata" -->
<meta name="description" content="京都・福知山の電気・通信・ITインフラ構築。【HRホールディングス】" />
<!-- InstanceEndEditable -->
<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
<meta name="format-detection" content="telephone=no">
<!-- InstanceBeginEditable name="head" -->
<meta property="og:url" content="https://horinet.co.jp/" />
<meta property="og:type" content="website" />
<meta property="og:title" content="京都・福知山の電気・通信・ITインフラ構築｜【HRホールディングス】" />
<meta property="og:description" content="京都・福知山のHRホールディングスは、電気工事・通信工事・ITサポートを行う専門会社。パソコン販売やネットワーク構築もお任せください。" />
<meta property="og:site_name" content="HRホールディングス" />
<meta property="og:image" content="https://horinet.co.jp/images/ogp.jpg" />
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
</head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MMT98P5K"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!--ヘッドナビ-->
<header id="header">
  <div id="h-box">
    <h1><a href="../"><img src="images/h-logo.svg" width="271" height="53" alt="HRホールディングス"/></a></h1>
    <div class="global-nav">
      <div class="global-nav-button">
        <div class="global-nav-button-icon"></div>
      </div>
      <div class="global-nav-item-list"> 
        <div class="global-nav-item"><a href="philosophy.html">Our Philosophy</a></div>
        <div class="global-nav-item"><a href="company.html">企業情報</a></div>
        <div class="global-nav-item"><a href="group.html">グループ企業</a>
          <div class="global-nav-sub-item-list">
            <div class="global-nav-sub-item"><a href="group.html">グループ企業一覧</a></div>
            <div class="global-nav-sub-item"><a href="https://www.horinet.co.jp/horicomm" target="_blank">株式会社堀通信</a></div>
            <div class="global-nav-sub-item"><a href="https://www.horinet.co.jp/horitech" target="_blank">株式会社 HoriTech</a></div>
          </div>
        </div>
        <div class="global-nav-item"><a href="facility.html">関連施設</a></div>
        <div class="global-nav-item"><a href="contact.php">お問い合わせ</a></div>
      </div>
    </div>
    <!-- .global-nav --> 
  </div>
</header>
<main> <!-- InstanceBeginEditable name="mainArea" --> 
  <!--動画-->
  <section id="movie">
    <div class="fv">
      <video autoplay muted playsinline loop preload="auto" class="pcOnly">
        <source src="main-mv.mp4" type="video/mp4">
        お使いのブラウザは動画に対応していません。
      </video>
      <video autoplay muted playsinline preload="auto" class="spOnly">
        <source src="main-mv-sp.mp4" type="video/mp4">
        お使いのブラウザは動画に対応していません。
      </video>
      <div class="text-overlay">
        <p>つながる力、<br>広がる価値</p>
      </div>
    </div>
  </section>
  
  <section class="index-text-area">
    <h2>未来の可能性は<br>人と人が「つながる」瞬間から<br class="spOnly">生まれます</h2>
    <p><a href="philosophy.html" class="main-btn">Our Philosophy<img src="images/arrow2.svg" alt=""/></a></p>
  </section>
  
  <section id="index-news" class="inner">
    <h2 class="news-title">News <span>―お知らせ―</span></h2>
    <!-- <div class="index-news-list">
      <p class="date">2025.10.01</p>
      <p class="cate"><span class="bg-or">その他</span></p>
      <p class="text"><a href="#">ダミーテキスト：ホームページリニューアル<img src="images/link-icon.svg" alt=""/></a></p>
    </div>
    <div class="index-news-list">
      <p class="date">2025.10.01</p>
      <p class="cate"><span class="bg-bl">堀通信</span></p>
      <p class="text"><a href="#">名刺管理ソフト SKYPCE 導入事例として連載・公開されました</a></p>
    </div>
    <div class="index-news-list">
      <p class="date">2025.10.01</p>
      <p class="cate"><span class="bg-bl">堀通信</span></p>
      <p class="text"><a href="#">日本電通株式会社様のホームページにて弊社の「抗ウイルス・除菌用紫外線照射装置」の導入事例が紹介されました<img src="images/link-icon.svg" alt=""/></a></p>
    </div> -->
    <?= $news_list ?>
    <div class="news-btn"><p><a href="news-list.php">一覧へ<img src="images/arrow2.svg" alt=""/></a></p></div>
  </section>
  <section id="index-menu">
    <div class="inner text-center">
    <h2 class="news-title">Group companies <br class="spOnly"><span>―グループ企業―</span></h2>
    </div>
    <div class="index-menu-box inner">
      <div class="index-menu-box-info">
        <figure><a href="https://www.horinet.co.jp/horicomm" target="_blank"><img src="images/group-1.svg" width="800" height="800" alt="企業情報"/></a></figure>
        <div class="horinet">
          <div>
            <h3>ICT事業</h3>
            <p>事業内容端的に（50文字程度）事業内容端的に（50文字程度）事業内容端的に（50文字程度）事業内容端的に（50文字程度）</p>
          </div>
        </div>
      </div>
      <div class="index-menu-box-info">
        <figure><a href="https://www.horinet.co.jp/horitech" target="_blank"><img src="images/group-2.svg" width="800" height="800" alt="ICT事業"/></a></figure>
        <div class="horitech">
          <div>
            <h3>電気・通信事業</h3>
            <p>公共・民間施設から道路や大規模ネットワークまで幅広い電気・通信設備工事を担う事業</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- InstanceEndEditable --> </main>
<!--フッター-->
<footer>
  <section class="f-contact">
    <div class="inner">
      <div class="logo-nav">
        <figure class="f-log"><a href="index.php"><img src="images/f-logo.svg" width="271" height="53" alt="HRホールディングス"/></a></figure>
        <ul>
          <li class="f-nav"><a href="philosophy.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>Our Philosophy</a></li>
          <li class="f-nav"><a href="company.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>企業情報</a></li>
          <li class="f-nav"><a href="group.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>グループ企業</a></li>
          <li class="f-nav"><a href="facility.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>関連施設</a></li>
          <li class="f-pv"><a href="news-list.php">お知らせ</a></li>
          <li class="f-pv"><a href="privacy.html">プライバシーポリシー</a></li>
        </ul>
      </div>
      <div class="tel-form">
        <p class="f-form"><a href="contact.php">お問い合わせ</a></p>
        <figure class="f-tel"><a href="tel:0773-45-7222"><img src="images/tel1.svg" alt="お問い合わせ電話番号"/></a></figure>
      </div>
    </div>
    <div class="bnr-area">
      <p>グループ<br class="spOnly">会社</p>
      <div>
        <figure><a href="https://www.horinet.co.jp/horicomm" target="_blank"><img src="images/group-1.svg" width="560" height="160" alt="株式会社堀通信"/></a></figure>
        <figure><a href="https://www.horinet.co.jp/horitech" target="_blank"><img src="images/group-2.svg" width="560" height="160" alt="株式会社 HoriTech"/></a></figure>
      </div>
    </div>
  </section>
  <p class="copy-r text-center">Copyright &copy; HR Holdings All Rights Reserved.</p>
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
  
<style>
  @media screen and (max-width: 1024px) {
    .global-nav-sub-item-list {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 0 20px;
    }
    .global-nav-sub-item a {
      padding-left: 0;
    }
  }
</style>
<!-- InstanceBeginEditable name="foot" -->
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>