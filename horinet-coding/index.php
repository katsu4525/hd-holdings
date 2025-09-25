<?php

/**
 * @file index.php
 * @brief 堀通信TOPページ
 * @date 2025-09-18
 *
 * Copyright isis Co.,ltd.
 */
use Stlib\Stlib\Stlib;

require_once(__DIR__ . "/../admin/common/common_setting.php");
require_once(__DIR__ . "/../admin/lib/Stlib.php");

$stlib = new Stlib();
$news_list = '';

try {
  $sql = "SELECT * FROM news_tb WHERE ne_is_public = 1 AND (ne_cate = '堀通信' OR ne_cate = 'その他') ORDER BY ne_created DESC LIMIT 3";
  $news_data = $db->setSql($sql);
  $news_data = $stlib->xssEs($news_data);

  foreach ((array)$news_data as $val) {
    switch ($val['ne_cate']) {
      // case 'HRホールディングス':
      //   $cate = '<span class="bg-pl">HRホールディングス</span>';
      //   break;
      case '堀通信':
        $cate = '<span class="bg-gr">堀通信</span>';
        break;
      // case 'HoriTech':
      //   $cate = '<span class="bg-gr">HoriTech</span>';
      //   break;
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
<html><!-- InstanceBegin template="/Templates/temp.dwt" codeOutsideHTMLIsLocked="false" -->
<head prefix="og: https://ogp.me/ns#">
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>京都・福知山の電気・通信・ITインフラ構築｜【株式会社堀通信】</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="descdata" -->
<meta name="description" content="京都・福知山の電気・通信・ITインフラ構築。【株式会社堀通信】" />
<!-- InstanceEndEditable -->
<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
<meta name="format-detection" content="telephone=no">
<!-- InstanceBeginEditable name="head" -->
<meta property="og:url" content="https://www.horinet.co.jp/horicomm/" />
<meta property="og:type" content="website" />
<meta property="og:title" content="京都・福知山の電気・通信・ITインフラ構築｜【株式会社堀通信】" />
<meta property="og:description" content="京都・福知山の株式会社堀通信は、電気工事・通信工事・ITサポートを行う専門会社。パソコン販売やネットワーク構築もお任せください。" />
<meta property="og:site_name" content="株式会社堀通信" />
<meta property="og:image" content="https://www.horinet.co.jp/horicomm/images/ogp.jpg" />
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
<!--ヘッドナビ-->
<header id="header">
  <div id="h-box">
    <h1><a href="index.php"><img src="images/h-logo.svg" width="271" height="53" alt="株式会社堀通信"/></a></h1>
    <div class="global-nav">
      <div class="global-nav-button">
        <div class="global-nav-button-icon"></div>
      </div>
      <div class="global-nav-item-list"> 
      <!-- SP用ロゴ -->
        <div class="global-nav-item"><a href="company.html">企業情報</a></div>
        <div class="global-nav-item"><a href="ict-business.html">ICT事業</a></div>
        <div class="global-nav-item"><a href="case.html">導入事例</a></div>
        <div class="global-nav-item"><a href="https://www.horinet.co.jp/contact.php">お問い合わせ</a></div>
        <figure class="remote-img"><a href="remote-maintenance.html"><img src="images/remote-h.svg" alt="リモートメンテナンス"/></a></figure>
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
        <p>通信と<br class="spOnly">電気で描く</p>
        <p>新しい時代</p>
      </div>
    </div>
  </section>
  <section class="index-text-area">
    <h2>経験×技術×統合力で<br>プロジェクトを成功に導く</h2>
  </section>
    <section id="index-news" class="inner">
      <h2 class="news-title">News <span>―お知らせ―</span></h2>
      <!-- <div class="index-news-list">
        <p class="date">2025.10.01</p>
        <p class="cate"><span class="bg-or">その他</span></p>
        <p class="text"><a href="#">ダミーテキスト：ホームページリニューアル</a></p>
      </div>
      <div class="index-news-list pcOnly">
        <p class="date">2025.10.01</p>
        <p class="cate"><span class="bg-gr">堀通信</span></p>
        <p class="text"><a href="#">ダミーテキスト：ホームページリニューアル</a></p>
      </div>
      <div class="index-news-list pcOnly">
        <p class="date">2025.10.01</p>
        <p class="cate"><span class="bg-gr">堀通信</span></p>
        <p class="text"><a href="#">ダミーテキスト：ホームページリニューアル<img src="images/link-icon.svg" alt=""/></a></p>
      </div> -->
      <?= $news_list ?>
      <div class="news-btn"><p><a href="news-list.php">一覧へ<img src="images/arrow1.svg" alt=""/></a></p></div>
    </section>
    <section id="index-menu">
      <div class="index-menu-box inner">
        <div class="index-menu-box-info">
          <figure><img src="images/index-img1.webp" width="800" height="800" class="pcOnly" alt="企業情報"/><img src="images/index-img1-sp.webp" width="800" height="800" class="spOnly" alt="企業情報"/></figure>
          <p><a href="company.html" class="menu-nav">企業情報<img src="images/arrow2.svg" alt=""/></a></p>
        </div>
        <div class="index-menu-box-info">
          <figure><img src="images/index-img2.webp" width="800" height="800" class="pcOnly" alt="ICT事業"/><img src="images/index-img2-sp.webp" width="800" height="800" class="spOnly" alt="ICT事業"/></figure>
          <p><a href="ict-business.html" class="menu-nav">ICT事業<img src="images/arrow2.svg" alt=""/></a></p>
        </div>
        <div class="index-menu-box-info">
          <figure><img src="images/index-img3.webp" width="800" height="800" class="pcOnly" alt="導入事例"/><img src="images/index-img3-sp.webp" width="800" height="800" class="spOnly" alt="導入事例"/></figure>
          <p><a href="case.html" class="menu-nav">導入事例<img src="images/arrow2.svg" alt=""/></a></p>
        </div>
      </div>
    </section>
    <section id="registration">
      <figure><img src="images/registration.png" width="772" height="290" alt=""/></figure>
      <p>【登録情報】</p>
      <p>・登録事業者：株式会社堀通信 開発営業部<br>
      ・適用規格：ISO/IEC 27001：2013<br class="spOnly">（JIS Q 27001：2014）<br>
      ・登録番号：IS 704449 / ISO27001</p>
    </section>
  <!-- InstanceEndEditable --> </main>
<!--フッター-->
<footer>
  <section class="f-contact">
    <div class="inner">
      <div class="logo-nav">
        <figure class="f-log"><a href="index.php"><img src="images/f-logo.svg" width="271" height="53" alt="株式会社堀通信"/></a></figure>
        <ul>
          <li class="f-nav"><a href="company.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>企業情報</a></li>
          <li class="f-nav"><a href="ict-business.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>ICT事業</a></li>
          <li class="f-nav"><a href="case.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>導入事例</a></li>
          <li class="f-nav"><a href="remote-maintenance.html"><span class="circle-icon">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M3 2L7 5L3 8" stroke="#333" stroke-width="1" fill="none"/>
          </svg>
          </span>リモートメンテナンス</a></li>
          <li class="f-pv"><a href="security.html">情報セキュリティ方針</a></li>
          <li class="f-pv"><a href="https://www.horinet.co.jp/privacy.html">プライバシーポリシー</a></li>
        </ul>
      </div>
      <div class="tel-form">
        <p class="f-form"><a href="https://www.horinet.co.jp/contact.php">お問い合わせ</a></p>
        <figure class="f-tel"><a href="tel:0773-22-1220"><img src="images/tel1.svg" alt="お問い合わせ電話番号"/></a></figure>
      </div>
    </div>
    <div class="bnr-area">
      <p>グループ<br class="spOnly">会社</p>
      <div>
        <figure><a href="https://www.horinet.co.jp/" target="_blank"><img src="images/group-1.svg" width="560" height="160" alt="HRホールディングス"/></a></figure>
        <figure><a href="https://www.horinet.co.jp/horitech" target="_blank"><img src="images/group-2.svg" width="560" height="160" alt="株式会社 HoriTech"/></a></figure>
      </div>
    </div>
  </section>
  <p class="copy-r text-center">Copyright &copy; Hori Communication Corp All Rights Reserved.</p>
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
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>