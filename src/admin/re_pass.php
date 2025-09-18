<?php

/**
 * @file re_pass.php
 * @brief パスワード再発行受付画面
 * @date 2023-08-29
 *
 * Copyright isis Co.,ltd.
 */

require_once(__DIR__ . "/common/common_setting.php");

$err_str = "";
// ログイン失敗時の処理
$err_code = filter_input(INPUT_GET, 'ER', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($err_code)) {
  $err_code = rawurldecode($err_code);
  $err_code = htmlspecialchars($err_code, ENT_QUOTES, 'UTF-8');
  switch ($err_code) {
    case 1:
      $err_str = "\"Login ID\"が違います。";
      // $err_str = "\"Login ID\"または\"PassWord\"が違います。";
      break;
    case 10:
      $err_str = "契約期間外となります。";
      break;
    case 100:
      $err_str = "タイムアウトしました。";
      break;
    case 200:
      $err_str = "不正を検知しました。";
      break;
    default:
      $err_str = "";
      break;
  }
  // else if ($err_code == 0) {
  //   $err_str = "パスワードをメールで送信しました。";
  // }
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
    #image-btn {
      background: <?= $DF_COLOR_LIGHT ?>;
      box-shadow: 0 3px 0 <?= $DF_COLOR_DARK ?>;
    }
  </style>
</head>

<body>
  <div id="container">
    <p id="loginLogo"><img src="img/logo.png" alt="ロゴ" width="173"></p>
    <p id="loginTitle">パスワード再発行受付画面</p>
    <form method="post" action="re_passfunc.php">
      <section id="login">
        <?= "<p class=\"red\">" . $err_str . "</p>"; ?>
        <p>IDを入力してください。</p>
        <dl class="clearfix">
          <dt>Login ID</dt>
          <dd>
            <input name="LoginID" type="text" id="LoginID" placeholder="メールアドレス" required />
          </dd>
        </dl>
        <!-- <p>メールアドレスを入力してください。</p>
        <dl class="clearfix">
          <dt>Mail</dt>
          <dd>
            <input name="Mail" type="text" id="Mail" placeholder="例：abcd0123" required />
          </dd>
        </dl> -->
        <center>
          <label>
            <input type="submit" value="再発行処理を申請する" id="image-btn" />
          </label>
          <p>ログインは<a href="login.php">こちら</a></p>
        </center>
      </section>
    </form>

    <div id="footer_l"><?= $DF_COPYRIGHT ?></div>
  </div>
</body>

</html>