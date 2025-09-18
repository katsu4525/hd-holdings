<?php

/**
 * @file re_passend.php
 * @brief パスワード変更予約完了画面
 * @date 2023-08-29
 *
 * Copyright isis Co.,ltd.
 */

require_once(__DIR__ . "/common/common_setting.php");

$err_str = "";
$err_code = filter_input(INPUT_GET, 'ER', FILTER_SANITIZE_SPECIAL_CHARS);
if (is_null($err_code)) {
  $err_code  = 100;
}
switch ($err_code) {
  case 0:
    $err_str = <<< "HTML"
      <h2>パスワードは変更されました。</h2>
      <p>ログイン画面からログインしてください。</p>
      <div class="center"><a href="login.php">ログイン画面はこちら</a></div>
    HTML;
    break;
  case 100:
    $err_str = <<< "HTML"
      <h2 class="red">エラーが発生しました。</h2>
      <p>お手数ですが、ログイン画面から操作をやり直してください。</p>
      <div class="center"><a href="login.php">ログイン画面はこちら</a></div>
    HTML;
    break;
  default:
    $err_str = <<< "HTML"
      <h2 class="red">エラーが発生しました。</h2>
      <p>お手数ですが、ログイン画面から操作をやり直してください。</p>
      <div class="center"><a href="login.php">ログイン画面はこちら</a></div>
    HTML;
    break;
}
?>
<!DOCTYPE HTML>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="robots" content="noindex" />
  <title>管理サイト</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Cache-Control" content="no-store">
  <meta http-equiv="Expires" content="0">
  <link href="css/base.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="css/font-awesome.css">
  <link rel="icon" href="favicon.ico" />
  <meta name="robots" content="noindex">
  <style>
    body {
      background:-webkit-gradient(linear, left top, left bottom, color-stop(1.00, <?= $DF_LOGIN_FROM ?>), color-stop(0.00, <?= $DF_LOGIN_TO ?>));
      background:-webkit-linear-gradient(<?= $DF_LOGIN_FROM ?>,<?= $DF_LOGIN_TO ?>);
      background:-moz-linear-gradient(<?= $DF_LOGIN_FROM ?>, <?= $DF_LOGIN_TO ?>);
      background:linear-gradient(<?= $DF_LOGIN_FROM ?>, <?= $DF_LOGIN_TO ?>);
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
  <script>
    $(function() {
      //画面初期表示時に遷移先nullの履歴を追加する
      history.pushState(null, null, null);

      //ブラウザの戻る／すすむボタンで発火するイベント
      window.onpopstate = function(event) {
        //戻るボタンを押して戻った時に再度nullの履歴を追加する。
        //※この処理はalertの前に書いておく必要あり。alertの後ろだと戻るボタンを連打したときに戻れてしまう。
        history.pushState(null, null, null);
        alert("戻るボタンは禁止されています。");
      };
    });
  </script>
</head>

<body>
  <div id="container">
    <p id="loginLogo"><img src="img/logo.png" width="173"></p>
    <p id="loginTitle">パスワード再設定画面</p>
    <form method="post">
      <section id="login">
        <?= $err_str ?>
      </section>
    </form>
    <div id="footer_l"><?= $DF_COPYRIGHT ?></div>
  </div>
</body>

</html>