<?php

/**
 * @file gauthentication_qr.php
 * @brief QRコード読み取り画面
 * @date 2023-07-21
 *
 * Copyright isis Co.,ltd.
 */

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");
require_once(__DIR__ . "/lib/GoogleAuthenticator.php");

session_start();

if (empty($_SESSION['logkey'])){
  header("Location: login.php?ER=99");
  exit;
}

$uq1 = filter_input(INPUT_GET, 'uq', FILTER_SANITIZE_SPECIAL_CHARS);
$uq2 = filter_input(INPUT_GET, 'ud', FILTER_SANITIZE_SPECIAL_CHARS);
$am_key = filter_input(INPUT_GET, 'am', FILTER_SANITIZE_SPECIAL_CHARS);
if (is_null($uq1) || is_null($uq2) || is_null($am_key)) {
  $err_code = 200;
}

if ((int)$_SESSION['logkey'] !== (int)$am_key){
  // header("Location: login.php?ER=99");
  // exit;
  $err_code = 99;
}

try{
  // 変更予約取得
  $sql = "SELECT * FROM suqr_tb WHERE sq_uniq1 = ? AND sq_uniq2 = ? AND sq_admin_key = ?";
  $bind = [];
  $bind[] = $uq1;
  $bind[] = $uq2;
  $bind[] = $am_key;
  $res = $db->setSql($sql, $bind, PDO::FETCH_ASSOC, "fetch");

  // ユーザー情報
  $sql = "SELECT * FROM admin_tb WHERE am_key = ?";
  $bind = [];
  $bind[] = $am_key;
  $res_user = $db->setSql($sql, $bind, PDO::FETCH_ASSOC, 'fetch');

} catch (Exception $e) {
  recordLog($e);
  header("Location: login.php?ER=99");
  exit;
}

$now = strtotime("now");
$err_code = 0;
if (!empty($res)) {
  if ($res['sq_limit'] == "" || $res['sq_limit'] < $now) {
    $err_code = 100;
  }
} else {
  $err_code = 50;
}

$ga = new PHPGangsta_GoogleAuthenticator();
if (empty($res_user['am_secret'])){
  // header("Location: login.php?ER=99");
  // exit;
  $err_code = 98;
} else {
  $secret = $res_user['am_secret'];
  // サービス名
  $title = $DF_APP_NAME;
  
  // ID
  $id = $res_user['am_id'];
  
  // QRコードURLの生成と表示
  $qr_url = $ga->getQRCodeGoogleUrl($id, $secret, $title);
  
  $options = stream_context_create(array('ssl' => array(
    'verify_peer'      => false,
    'verify_peer_name' => false
  )));
  
  $qr_image_src = file_get_contents($qr_url, false, $options);
  $qr_image_b64 = base64_encode($qr_image_src);
}


$err_str = "";
// ログイン失敗時の処理
switch ($err_code) {
  case 10:
    $err_str = <<< "HTML"
      <h2 class="red">契約期間外となります。</h2>
    HTML;
    break;
  case 50:
    $err_str = <<< "HTML"
      <h2 class="red">QRコード発行の受付がありません。</h2>
    HTML;
    break;
  case 98:
  case 99:
    $err_str = <<< "HTML"
      <h2 class="red">エラーが発生しました。</h2>
      <p>お手数ですが、ログイン画面から操作をやり直してください。</p>
      <div class="center"><a href="login.php">ログイン画面はこちら</a></div>
    HTML;
    break;
  case 100:
    $err_str = <<< "HTML"
      <h2 class="red">タイムアウトしました。</h2>
    HTML;
    break;
  case 200:
    $err_str = <<< "HTML"
      <h2 class="red">不正を検知しました。</h2>
    HTML;
    break;
  default:
    $err_str = "";
    break;
}

$inp  = "";
if ($err_code == 0) {
  $inp = <<< "HTML"
    <center>
      <p>QRコードを読み込んで「google認証」に追加してください。</p><br>
      <img src="data:image/png;base64, {$qr_image_b64}">
      <p>認証コード入力画面は<a href="gauthentication.php">こちら</a></p>
    </center>
  HTML;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="robots" content="noindex" />
  <title>管理サイト</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Cache-Control" content="no-store">
  <meta http-equiv="Expires" content="0">
  <!--ファビコン-->
  <link rel="icon" type="image/png" href="../img/favicon.png">
  <link href="css/base.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="css/font-awesome.css">
  <!-- <link rel="icon" href="favicon.ico" /> -->
  <meta name="robots" content="noindex">
  <style>
      #LoginID,
      #PassWord {
          width: 98%;
      }
      body {
        background:-webkit-gradient(linear, left top, left bottom, color-stop(1.00, #F7F7F7), color-stop(0.00, rgb(213, 214, 235)));
      background:-webkit-linear-gradient(#F7F7F7, rgb(213, 214, 235));
      background:-moz-linear-gradient(#F7F7F7, rgb(213, 214, 235));
      background:linear-gradient(#F7F7F7, rgb(213, 214, 235));
        /* background: linear-gradient(#F7F7F7, rgb(210, 211, 243)); */
        /* background: linear-gradient(#F7F7F7, rgb(192, 192, 192)); */
      }
      h2, p {
        text-align: center;
      }
      h2 {
        font-size: 120%;
        margin-bottom: 10px;
      }
      #login .red {
        margin-top: 0;
      }
      .center {
        text-align: center;
        margin-top: 20px;
      }
  </style>
</head>
<body>
  <div id="container">
    <p id="loginLogo"><img src="img/logo.png" width="173"></p>
    <p id="loginTitle">QR発行画面</p>
    <section id="login">
      <?= $err_str ?>
      <?= $inp ?>
    </section>

    <div id="footer_l"><?= $DF_COPYRIGHT ?></div>
  </div>
</body>

</html>