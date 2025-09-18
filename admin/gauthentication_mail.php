<?php

/**
 * @file gauthentication.php
 * @brief google認証画面
 * @date 2023-03-22
 *
 * Copyright isis Co.,ltd.
 */
require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");

session_start();

if (empty($_SESSION['logkey'])){
  header("Location: login.php?ER=99");
  exit;
}

$error = filter_input(INPUT_GET, 'ER', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$err_str = '';
if (!empty($error)) {
  $err_str = "<p style='color: red;'>再度入力してください。</p>";
}

try{
  // ユーザー情報
  $sql = "SELECT * FROM admin_tb WHERE am_key = ?";
  $bind = [];
  $bind[] = $_SESSION['logkey'];
  $res_user = $db->setSql($sql, $bind, PDO::FETCH_ASSOC, 'fetch');

  if (empty($res_user)){
    header("Location: login.php?ER=99");
    exit;
  }
} catch (Exception $e) {
  recordLog($e);
  header("Location: login.php?ER=99");
  exit;
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
      p {
        text-align: center;
      }
      .center {
        text-align: center;
        margin-top: 20px;
      }
  </style>
</head>
<body>
  <div id="container">
    <p id="loginLogo"><img src="img/logo.png" width="173"></p><br>
    <p id="loginTitle">QRコード発行受付画面</p>
    <form method="post" action="gauthentication_send.php">
      <section id="login">
        <p>再度 Login ID（メールアドレス）を入力してください</p>
        <p>※入力いただいたメールアドレスにQRコード発行用URLを送信します。</p>
        <p class="red" style="text-align:center; margin-top:10px;"><?= $err_str ?></p>
        <dl class="clearfix">
          <!-- <dt>Login ID</dt> -->
          <dd style="text-align:center; width:100%;">
            <input style="width:60%;" name="mail" type="mail" placeholder="Login ID（メールアドレス）を入力してください" required>
          </dd>
        </dl>
        <center>
          <label>
            <input type="submit" value="QRコード発行" id="image-btn" />
          </label>
        </center>
        <div class="center"><a href="gauthentication.php">認証コード入力画面に戻る</a></div>
      </section>
    </form><br>

    <div id="footer_l"><?= $DF_COPYRIGHT ?></div>
  </div>
</body>

</html>