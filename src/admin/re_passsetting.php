<?php

/**
 * @file re_passsetting.php
 * @brief パスワード再設定画面
 * @date 2023-08-29
 *
 * Copyright isis Co.,ltd.
 */

require_once(__DIR__ . "/common/common_setting.php");

$uq1 = filter_input(INPUT_GET, 'uq', FILTER_SANITIZE_SPECIAL_CHARS);
$uq2 = filter_input(INPUT_GET, 'ud', FILTER_SANITIZE_SPECIAL_CHARS);
$am_key = filter_input(INPUT_GET, 'am', FILTER_SANITIZE_SPECIAL_CHARS);
if (is_null($uq1) || is_null($uq2) || is_null($am_key)) {
  $err_code = 200;
}

try {
  // 変更予約取得
  $sql = "SELECT * FROM supass_tb WHERE sp_uniq1 = ? AND sp_uniq2 = ? AND sp_admin_key = ?";
  $bind = array();
  $bind[] = $uq1;
  $bind[] = $uq2;
  $bind[] = $am_key;
  $res = $db->setSql($sql, $bind, PDO::FETCH_ASSOC, "fetch");

} catch(Exception $e){
  // echo $e->getMessage();
  header("Location: re_passend.php?ER=100");
  exit;
}

$now = strtotime("now");
$err_code = 0;
if (!empty($res)) {
  if ($res['sp_limit'] == "" || $res['sp_limit'] < $now) {
    $err_code = 100;
  }
} else {
  $err_code = 50;
}

$err_str = "";
// エラー時の処理
switch ($err_code) {
  case 10:
    $err_str = <<< "HTML"
      <h2 class="red">契約期間外となります。</h2>
    HTML;
    break;
  case 50:
    $err_str = <<< "HTML"
      <h2 class="red">パスワード再発行の受付がありません。</h2>
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
    <p>パスワードの再設定を行います。</p>
    <dl class="clearfix">
      <dt class="newPass">新規パスワード</dt>
      <dd>
        <input name="new" id="new" type="password" style="width:98%" />
        <span class="field-icon">
          <!-- <i toggle="password-field" class="mdi mdi-eye toggle-password"></i> -->
          <img src="img/eye-off.svg" alt="" class="toggle-password">
        </span>
      </dd>
      <p id="new_err" class="red"></p>
      <p class="center">6文字以上、50文字以下、英数字・ハイフン・アンダースコアのみご利用いただけます。</p>
      <dt>確認</dt>
      <dd>
        <input name="conf" type="password" style="width:98%" />
        <span class="field-icon">
          <!-- <i toggle="password-field" class="mdi mdi-eye toggle-password"></i> -->
          <img src="img/eye-off.svg" alt="" class="toggle-password">
        </span>
      </dd>
      <p id="conf_err" class="red"></p>
    </dl>
    <center>
      <label>
        <input type="hidden" name="am" value="{$am_key}">
        <input type="submit" value="変更する" id="image-btn" />
      </label>
      <p>ログインは<a href="login.php">こちら</a></p>
    </center>
  HTML;
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="./js/jquery.validate.min.js"></script>
  <script src="./validation/repass.js"></script>
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
    #login .newPass {
      font-size: 15px;
      padding: 13.8px 5px 15px 15px;
    }
  </style>
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
        // let input = $('#PassWord');
        let input = $(this).closest("dd").find("input");
        // type切替
        if (input.attr("type") == "password") {
          input.attr("type", "text");
        } else {
          input.attr("type", "password");
        }
      });
    });
  </script>
</head>

<body>
  <div id="container">
    <p id="loginLogo"><img src="img/logo.png" alt="ロゴ" width="173"></p>
    <p id="loginTitle">パスワード再設定画面</p>
    <form method="post" action="re_passup.php" id="pwd">
      <section id="login">
        <?= $err_str ?>
        <?= $inp ?>
      </section>
    </form>
  </div>
  <div id="footer_l"><?= $DF_COPYRIGHT ?></div>
</body>

</html>