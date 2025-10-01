<?php

/************************************
 ** login.php
 ** ログイン画面
 ***********************************/
require_once(__DIR__ . "/common/common_setting.php");

$err_str = "";
// ログイン失敗時の処理
$err_code = filter_input(INPUT_GET, 'ER', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($err_code)) {
  $err_code = rawurldecode($err_code);
  $err_code = htmlspecialchars($err_code, ENT_QUOTES, 'UTF-8');
  switch ($err_code) {
    case 1:
    case 2:
    case 3:
      $err_str = "\"Login ID\" または \"PassWord\" が違います。";
      break;
    case 10:
      $err_str = "パスワードが変更されました。再度ログインしてください。";
      break;
    case 97:
    case 98:
    case 99:
      $err_str = "エラーが発生しました。";
      break;
    case 100:
      $err_str = "タイムアウトしました。";
      break;
    case 200:
      $err_str = "不正を検知しました。";
      break;
    case 999:
      $err_str = "ログアウトしました。";
      break;
    default:
      $err_str = "";
      break;
  }
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
  <!--ファビコン-->
  <link rel="icon" type="img/png" href="../img/favicon.png">
  <!-- <link href="css/reset.css" rel="stylesheet" type="text/css"> -->
  <link href="css/base.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="css/font-awesome.css">
  <!-- <link rel="icon" href="favicon.ico" /> -->
  <meta name="robots" content="noindex">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    // パスワードの表示・非表示切替
    $(function() {
      $(".toggle-password").on('click', function() {
        // iconの切り替え
        // $(this).toggleClass("mdi-eye mdi-eye-off");
        let image = $(this).attr("src");
        if (image === "img/eye-off.svg"){
          image = "img/eye.svg";
        } else {
          image = "img/eye-off.svg";
        }
        $(this).attr("src", image);
        // 入力フォームの取得
        let input = $('#PassWord');
        // type切替
        if (input.attr("type") == "password") {
          input.attr("type", "text");
        } else {
          input.attr("type", "password");
        }
      });
    });
  </script>
  <style>
    body {
      background:-webkit-gradient(linear, left top, left bottom, color-stop(1.00, <?= $DF_LOGIN_FROM ?>), color-stop(0.00, <?= $DF_LOGIN_TO ?>));
      background:-webkit-linear-gradient(<?= $DF_LOGIN_FROM ?>,<?= $DF_LOGIN_TO ?>);
      background:-moz-linear-gradient(<?= $DF_LOGIN_FROM ?>, <?= $DF_LOGIN_TO ?>);
      background:linear-gradient(<?= $DF_LOGIN_FROM ?>, <?= $DF_LOGIN_TO ?>);
    }
    #image-btn {
      background: <?= $DF_COLOR_LIGHT ?>;
      box-shadow: 0 3px 0 <?= $DF_COLOR_DARK ?>;
    }
  </style>
</head>

<body>
  <div id="container">
    <p id="loginLogo"><img src="img/logo.png" width="173"></p>
    <p id="loginTitle">管理者ログイン画面</p>
    <form method="post" action="login_chk.php">
      <section id="login">
        <p>IDとパスワードを入力し、ログインして下さい。</p>
        <p class="red"><?= $err_str ?></p>
        <dl class="clearfix">
          <dt>Login ID</dt>
          <dd>
            <input name="LoginID" type="text" id="LoginID" placeholder="例：user" required />
          </dd>
          <dt>PassWord</dt>
          <dd>
            <input name="PassWord" type="password" id="PassWord" placeholder="例：abcd0123" required />
            <span class="field-icon">
              <!-- <i toggle="password-field" class="mdi mdi-eye toggle-password"></i> -->
              <img src="img/eye-off.svg" alt="" class="toggle-password">
            </span>
          </dd>
        </dl>
        <center>
          <label>
            <input type="submit" value="LOGIN" id="image-btn" />
          </label>
          <!-- <p>パスワードを忘れた方は<a href="re_pass.php">こちら</a></p> -->
        </center>
      </section>
    </form>

    <div id="footer_l"><?= $DF_COPYRIGHT ?></div>
  </div>
</body>

</html>