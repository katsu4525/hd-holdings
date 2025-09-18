<?php
require "toiawase/inc_postmail.php";
$str = make_token();
?>

<!doctype html>
<html><!-- InstanceBegin template="/Templates/tempHD.dwt" codeOutsideHTMLIsLocked="false" -->
<head prefix="og: https://ogp.me/ns#">
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>お問い合わせ｜京都・福知山の電気・通信・ITインフラ構築 【HRホールディングス】</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="descdata" -->
<meta name="description" content="お問い合わせページです" />
<!-- InstanceEndEditable -->
<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
<meta name="format-detection" content="telephone=no">
<!-- InstanceBeginEditable name="head" -->
<meta property="og:url" content="https://horinet.co.jp/contact.php" />
<meta property="og:type" content="article" />
<meta property="og:title" content="お問い合わせ｜京都・福知山の電気・通信・ITインフラ構築 【HRホールディングス】" />
<meta property="og:description" content="お問い合わせページです" />
<meta property="og:site_name" content="HRホールディングス" />
<meta property="og:image" content="https://horinet.co.jp/images/ogp.jpg" />
<link rel="stylesheet" href="./css/contact.css">
<script type="text/javascript" src="./form/util.js"></script>
<script type="text/javascript" src="./form/validator.js"></script>
<script type="text/javascript" src="./form/yubinbango.js" charset="UTF-8"></script>
<link rel="stylesheet" href="./form/default.css" type="text/css">
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
            <div class="global-nav-sub-item"><a href="#">株式会社堀通信</a></div>
            <div class="global-nav-sub-item"><a href="#">株式会社 HoriTech</a></div>
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
  <div class="logo-area inner">
    <figure><img src="images/f-logo.svg" alt=""/></figure>
    <div>
      <figure><img src="images/group-1.svg" alt=""/></figure>
      <figure><img src="images/group-2.svg" alt=""/></figure>
    </div>
  </div>
  <section class="title-area-news">
    <h2>お問い合わせ</h2>
  </section>
  <section id="contact">
		<section id="form">
			<form action="toiawase/design_toiawase.php" method="post" onsubmit="return Validator.submit(this)">
				<!-- 添付がある場合は下記のタグ
			<form action="toiawase/design_toiawase.php" method="post" onsubmit="return Validator.submit(this)" enctype="multipart/form-data"> -->

				<input name="himitsu" type="hidden" id="himitsu" value="<?= $str ?>" />

				<!-- 必須項目 -->
				<input name="need" type="hidden" id="need" value="お名前 email email2 電話番号 お問い合わせ内容" />

				<!-- 修正画面を表示用設置 -->
				<input type="hidden" name="disp[お名前]" value="text 100">
				<input type="hidden" name="disp[貴社名]" value="text 100">
				<input type="hidden" name="disp[email]" value="text 100">
				<input type="hidden" name="disp[email2]" value="text 100">
				<input type="hidden" name="disp[電話番号]" value="text 100">
				<input type="hidden" name="disp[お問い合わせ内容]" value="textarea 5 55">
				<input type="hidden" name="disp[プライバシーポリシー]" value="check 同意する">
				<!-- 入力部分 -->
				<dl class="clearfix">
					<dt>お名前<span class="must">必須</span></dt>
					<dd class="boxW100">
						<input name="お名前" type="text" id="お名前" onblur="Validator.check(this)" />
					</dd>
					<dt>貴社名</dt>
					<dd class="boxW100">
						<input name="貴社名" type="text" id="貴社名" />
					</dd>
<!--
					 <dt>住所</dt>
				<dd class="boxW100">
					<div class="h-adr">
						<span class="p-country-name" style="display:none;">Japan</span>
						<div class="boxW40">〒<input type="tel" name="郵便番号" class="p-postal-code" maxlength="8" placeholder="例：5420081"></div>
						<span>※ハイフン(－)なしでご入力ください</span><br>
						<input name="ご住所" type="text" class="p-region p-locality p-street-address p-extended-address" value="" />
						<br>
						<span>※英数字は半角でご入力ください</span>
					</div> 
					</dd>
-->
					<dt>メールアドレス<span class="must">必須</span></dt>
					<dd class="boxW100">
						<input name="email" type="email" id="email" onblur="Validator.check(this, 'equal mail', 'email2')" />
						<br>
						※半角英数字でご入力ください<br>
						<input name="email2" type="email" id="email2" placeholder="確認のためもう一度ご入力ください" onblur="Validator.check(this, 'equal mail', 'email')" />
					</dd>
					<dt>電話番号<span class="must">必須</span></dt>
					<dd class="boxW50">
						<input name="電話番号" type="tel" id="電話番号" onblur="Validator.check(this)" />
					</dd>
					<dt>お問い合わせ内容<span class="must">必須</span></dt>
					<dd class="boxW100">
						<textarea name="お問い合わせ内容" rows="6" id="お問い合わせ内容" onblur="Validator.check(this)"></textarea>
					</dd>
					<dt>プライバシーポリシー<span class="must">必須</span></dt>
					<dd>
						<label>
							<input name="プライバシーポリシー" type="checkbox" id="プライバシーポリシー" value="同意する" onblur="Validator.check(this)">
							<input name="プライバシーポリシー" type="hidden" id="プライバシーポリシー" value="同意する">同意する　《<a href="privacy.html">プライバシーポリシー</a>》
						</label>
					</dd>
				</dl>
				<p id="image-btn">
					<input type="submit" value="内容を確認する" />
				</p>
			</form>
		</section>
  </section>
<!-- InstanceEndEditable --> </main>
<!--フッター-->
<footer>
  <section class="f-contact">
    <div class="inner">
      <div class="logo-nav">
        <figure class="f-log"><img src="images/f-logo.svg" width="271" height="53" alt="HRホールディングス"/></figure>
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
          <li class="f-pv"><a href="news-list.html">お知らせ</a></li>
          <li class="f-pv"><a href="privacy.html">プライバシーポリシー</a></li>
        </ul>
      </div>
      <div class="tel-form">
        <p class="f-form"><a href="contact.php">お問い合わせ</a></p>
        <figure class="f-tel"><a href="tel:00-0000-0000"><img src="images/tel1.svg" alt="お問い合わせ電話番号"/></a></figure>
      </div>
    </div>
    <div class="bnr-area">
      <p>グループ<br class="spOnly">会社</p>
      <div>
        <figure><a href="" target="_blank"><img src="images/group-1.svg" width="560" height="160" alt="HRホールディングス"/></a></figure>
        <figure><a href="" target="_blank"><img src="images/group-2.svg" width="560" height="160" alt="株式会社 HoriTech"/></a></figure>
      </div>
    </div>
  </section>
  <p class="copy-r text-center">&copy; 2017 Hori Communication Corp All Rights Reserved.</p>
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
