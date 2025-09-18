<?php

/**
 * @file news.php
 * @brief 記事登録
 * @date 2025-09-03
 *
 * Copyright isis Co.,ltd.
 */

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");
chk_session(DF_SES_NAM);

require_once(__DIR__ . "/temple.php");
require_once(__DIR__ . "/lib/message.php");

$today = date('Y.m.d');

$list = <<< "HTML"
  <div class="pankuzu">
    <p>記事管理</p>
    <span>></span>
    <p>記事登録</p>
  </div>
  <h2 style="display:flex">
    <img src="img/icon02.png" width="30" height="30">
    記事登録
  </h2>
  {$message_dialog}<br>
  <div class="reg">
    <dl>
      <form action="news_add.php" method="POST" id="news" enctype="multipart/form-data">
        <dt>記事登録</dt>
        <dd>
          <div class="detail">
            <div class="row">
              <div class="title">登録日</div>
              <div class="content">
                <label class="date_label">
                  <input type="text" name="created" id="datetimepicker" autocomplete="off" value="{$today}">
                  <img src="./img/calendar.svg" alt="カレンダーのアイコン">
                </label>
              </div>
            </div>
            <div class="row">
              <div class="title">公開設定</div>
              <div class="content">
                <label><input type="radio" name="is_public" id="" value="1" checked> 公開</label>
                <label><input type="radio" name="is_public" id="" value="2"> 非公開</label>
              </div>
            </div>
            <div class="row">
              <div class="title">カテゴリー</div>
              <div class="content">
                <select name="cate" id="">
                  <option value="HRホールディングス">HRホールディングス</option>
                  <option value="HoriTech">HoriTech</option>
                  <option value="堀通信">堀通信</option>
                  <option value="その他">その他</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="title">タイトル</div>
              <div class="content">
                <input type="text" class="w70" name="title">
              </div>
            </div>
            <div class="row">
              <div class="title">メイン画像</div>
              <div class="content">
                <div class="dropzone w20" id="main_dropzone"></div>
              </div>
            </div>
            <div class="row">
              <div class="title">内容</div>
              <div class="content">
                <textarea name="content" id=""></textarea>
              </div>
            </div>
            <div class="row">
              <div class="title">サブ画像（最大10枚）</div>
              <div class="content">
                <div class="dropzone w100" id="sub_dropzone"></div>
              </div>
            </div>
          </div>
        </dd>
        <div class="center mb10 mt15"><input type="submit" class="submit" value="登録する"></div>
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
    #mainArea .dz-button img {
      display: inline-block;
      margin: 10px;
    }
  </style>
HTML;

$js = <<< "HTML"
  <script src="./validation/news.js"></script>
  <script src="./js/jquery.datetimepicker.full.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
  <script src="./js/dropzone-setting.js"></script>
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
JS;

read_template($css, $js, $script, $list);