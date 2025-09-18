<?php

/**
 * @file gauthentication_lock.php
 * @brief ログインロック画面
 * @date 2023-11-29
 *
 * Copyright isis Co.,ltd.
 */
require_once(__DIR__ . "/common/common_setting.php");

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
    <p id="loginLogo"><img src="img/logo.png" width="173"></p><br>
    <p id="loginTitle">エラー画面</p>
    <section id="login">
      <h2 class="red">アカウントがロックされています。</h2>
      <p>30分後にロックが解除されるまでご利用いただけません。</p>
    </section>
    <div id="footer_l"><?= $DF_COPYRIGHT ?></div>
  </div>
</body>

</html>