<?php

/**
 * @file news-edit.php
 * @brief 記事編集画面
 * @date 2025-09-11
 *
 * Copyright isis Co.,ltd.
 */

use Stlib\Stlib\Stlib;

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");
chk_session(DF_SES_NAM);

require_once(__DIR__ . "/temple.php");
require_once(__DIR__ . "/lib/Stlib.php");
require_once(__DIR__ . "/lib/message.php");

$stlib = new Stlib();

$input_name = [];
$input_name[] = 'ne'; // 記事キー

$get = $stlib->getGET($input_name);

try {
  $sql = 'SELECT * FROM news_tb WHERE ne_key = ?';
  $bind = [];
  $bind[] = $get['ne'];
  $news_data = $db->setSql($sql, $bind, PDO::FETCH_ASSOC, 'fetch');

} catch (Exception $e) {
  recordLog($e);
  header("Location: news.php?MS=100");
  exit;
}

$list = <<< "HTML"
  <div class="pankuzu">
    <p>記事管理</p>
    <span>></span>
    <a href="news-list.php">記事一覧</a>
    <span>></span>
    <p>記事編集</p>
  </div>
  <h2 style="display:flex">
    <img src="img/icon02.png" width="30" height="30">
    記事編集
  </h2>
  {$message_dialog}<br>
  <div class="reg">
    <dl>
      <form action="news_update.php" method="POST" id="news">
        <dt>記事編集</dt>
        <dd>
          <div class="detail">
            <div class="row">
              <div class="title">登録日</div>
              <div class="content">
                <label class="date_label">
                  <input type="text" name="created" id="datetimepicker" autocomplete="off" value="{$news_data['ne_created']}">
                  <img src="./img/calendar.svg" alt="カレンダーのアイコン">
                </label>
              </div>
            </div>
            <div class="row">
              <div class="title">公開設定</div>
              <div class="content">
                <label><input type="radio" name="is_public" id="" value="1" {$stlib->chkVal('1', $news_data['ne_is_public'])} > 公開</label>
                <label><input type="radio" name="is_public" id="" value="2" {$stlib->chkVal('2', $news_data['ne_is_public'])}> 非公開</label>
              </div>
            </div>
            <div class="row">
              <div class="title">カテゴリー</div>
              <div class="content">
                <select name="cate" id="">
                  <option value="HRホールディングス" {$stlib->selectVal('HRホールディングス', $news_data['ne_cate'])} >HRホールディングス</option>
                  <option value="HoriTech" {$stlib->selectVal('HoriTech', $news_data['ne_cate'])} >HoriTech</option>
                  <option value="堀通信" {$stlib->selectVal('堀通信', $news_data['ne_cate'])} >堀通信</option>
                  <option value="その他" {$stlib->selectVal('その他', $news_data['ne_cate'])} >その他</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="title">タイトル</div>
              <div class="content">
                <input type="text" class="w70" name="title" value="{$news_data['ne_title']}">
              </div>
            </div>
            <div class="row">
              <div class="title">リンクURL</div>
              <div class="content">
                <input type="text" class="w70" name="url" id="url" placeholder="https://example.com" value="{$news_data['ne_url']}"><br>
                <p class="checkURL">URLのリンク先を確認する</p>
              </div>
            </div>
          </div>
        </dd>
        <div class="center mb10 mt15">
          <input type="hidden" name="ne" value="{$news_data['ne_key']}">
          <input type="submit" class="submit" value="更新する">
        </div>
      </form>
    </dl>
  </div>
HTML;

$css = <<< "HTML"
  <link rel="stylesheet" href="./css/jquery.datetimepicker.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" type="text/css" />
  <link rel="stylesheet" href="./css/dropzone.css">
  <style>
    .center {
      text-align:center;
    }
    .mb10 {
      margin-bottom:10px;
    }
    .mt15 {
      margin-top: 15px;
    }
    #mainArea dd .date_label {
      display: inline-block;
      margin-right: 60px;
      position: relative;
      width: 10%;
      min-width: 150px;
    }
    .date_label img {
      display: inline-block;
      position: absolute;
      right: -60px;
      top: 12px;
    }
    .w70 {
      width: 70%;
    }
    .w100 {
      width: 100%;
    }
    .checkURL {
      color: #06C;
      text-decoration: underline;
      cursor: pointer;
      display: inline-block;
      margin-top: 10px;
    }
  </style>
HTML;

$js = <<< "HTML"
  <script src="./validation/news.js"></script>
  <script src="./js/jquery.datetimepicker.full.js"></script>
HTML;

$script = <<< "JS"
  // datetimepicker設定
  $.datetimepicker.setLocale('ja');
  $('#datetimepicker').datetimepicker({
    timepicker: false,
    format: 'Y.m.d',
    scrollMonth: false,
    scrollInput: false,
    // minDate: 0,
  });

  $('.checkURL').click(function(){
    const urlVal = $('#url').val();
    window.open(urlVal, "_blank");
  });
JS;

read_template($css, $js, $script, $list);