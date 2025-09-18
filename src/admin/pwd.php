<?php

/**
 * @file pwd.php
 * @brief パスワード設定
 * @date 2023-08-21
 *
 * Copyright isis Co.,ltd.
 */

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");
chk_session(DF_SES_NAM);

require_once(__DIR__ . "/temple.php");
require_once(__DIR__ . "/lib/message.php");

$list = <<< "HTML"
  <div class="pankuzu">
    <p>マスター</p>
    <span>></span>
    <p>パスワード変更</p>
  </div>
  <h2 style="display:flex">
    <img src="img/icon02.png" width="30" height="30">
    パスワード変更
  </h2>
  {$message_dialog}<br>
  <div class="reg">
    <dl>
      <form action="pwd_update.php" method="POST" id="pwd">
        <dt>現在のパスワード</dt>
        <dd>
          <input type="text" name="old" style="width:40%" required>
        </dd>
        <dt>新しいパスワード</dt>
        <dd>
          <input type="text" name="new" id="new" style="width:40%" required>
        </dd>
        <dt>新しいパスワード（確認用）</dt>
        <dd>
          <input type="text" name="conf" style="width:40%" required>
        </dd>
        <p class="center">6文字以上、50文字以下、英数字・ハイフン・アンダースコアのみご利用いただけます。</p>
        <div class="center"><input type="submit" class="submit" value="変更する"></div>
      </form>
    </dl>
  </div>
HTML;

$css = <<< "HTML"
  <style>
    .center {
      text-align:center;
      margin-bottom:10px;
      margin-top: 15px;
    }
  </style>
HTML;

$js = <<< "HTML"
  <script src="./validation/pwd.js"></script>
HTML;

$script = <<< "JS"
  // $('form').submit(function(){
  //   if (!confirm('パスワード変更後\\n一度ログアウトします。\\n変更してよろしいですか？')){
  //     return false;
  //   }
  // });
  $('.submit').on('click', async function(e){
    e.preventDefault();

    const result = $('form').valid();
    if (!result) return false;

    if (await confdialog('パスワード変更後ログアウトします。<br>変更してよろしいですか？')){
      loading();
      $(this).closest('form').submit();
    }
  });
JS;

read_template($css, $js, $script, $list);