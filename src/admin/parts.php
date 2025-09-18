<?php

/**
 * @file pwd.php
 * @brief パスワード設定
 * @date 2023-08-21
 *
 * Copyright isis Co.,ltd.
 */

use Stlib\Stlib\Stlib;

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");
chk_session(DF_SES_NAM);

require_once(__DIR__ . "/temple.php");
require_once(__DIR__ . "/lib/message.php");
require_once(__DIR__ . "/lib/Stlib.php");

$stlib = new Stlib();
$show_cnt = 20;

$list = <<< "HTML"
  <div class="pankuzu">
    <a class="menu" href="javascript:void(0)" tabindex="-1">マスター</a>
    <span>></span>
    <a href="#">パーツサンプル</a>
    <span>></span>
    <p>パーツサンプル</p>
  </div>
  <h2 style="display:flex">
    <img src="img/icon02.png" width="30" height="30">
    パーツサンプル
  </h2>
  {$message_dialog}<br>
  <div class="reg">
    <dl>
      <form action="" method="POST" id="">
        <dt>フォーム要素</dt>
        <dd>
          <input type="text" class="w40" name="">
          <br><br>
          <select name="" id="">
            <option value="">【選択して下さい】</option>
            <optgroup label="北海道・東北地方">
              <option value="北海道">北海道</option>
              <option value="青森県">青森県</option>
              <option value="岩手県">岩手県</option>
              <option value="秋田県">秋田県</option>
              <option value="宮城県">宮城県</option>
              <option value="山形県">山形県</option>
              <option value="福島県">福島県</option>
            </optgroup>
            <optgroup label="関東地方">
              <option value="栃木県">栃木県</option>
              <option value="群馬県">群馬県</option>
              <option value="茨城県">茨城県</option>
              <option value="埼玉県">埼玉県</option>
              <option value="東京都">東京都</option>
              <option value="千葉県">千葉県</option>
              <option value="神奈川県">神奈川県</option>
            </optgroup>
          </select>
          <br><br>
          <label><input type="checkbox" name="" id=""> 野球</label>
          <label><input type="checkbox" name="" id=""> サッカー</label>
          <label><input type="checkbox" name="" id=""> テニス</label>
          <br><br>
          <label><input type="radio" name="" id=""> はい</label>
          <label><input type="radio" name="" id=""> いいえ</label>
          <br><br>
          <textarea name="" id="" cols="30" rows="10"></textarea>
          <br><br>
          <p>テキストが入ります</p>
          <br><br>
          <input type="submit" class="submitbtn"  value="送信">
          <br><br>
          <input type="submit" class="submit" value="送信">
          <br><br>
        </dd>
      </form>
      <dt>検索</dt>
      <dd>
        <form action="#" class="search_form_wrap" method="GET">
          <div class="search_form">
            <label><span>受講者氏名</span><input type="text" name=""></label>
            <label><span>受講者氏名（カナ）</span><input type="text" name=""></label>
          </div>
          <div class="search_form">
            <label><span>電話番号</span><input type="text" name=""></label>
            <label><span>事業所（カナ）</span><input type="text" name=""></label>
          </div>
          <input type="submit" class="search_submit submit" value="検索">
          <input type="hidden" name="cnt" value="{$show_cnt}">
        </form>
      </dd>
      <form action="test.php" method="POST" id="sort_form">
        <dt>リスト</dt>
        <div class="showCount">
          <p>表示件数：<select name="show_cnt">
            <option value="1"  {$stlib->selectVal('1', (string)$show_cnt)}>1</option>
            <option value="5"  {$stlib->selectVal('5', (string)$show_cnt)}>5</option>
            <option value="10" {$stlib->selectVal('10', (string)$show_cnt)}>10</option>
            <option value="20" {$stlib->selectVal('20', (string)$show_cnt)}>20</option>
            <option value="40" {$stlib->selectVal('40', (string)$show_cnt)}>40</option>
            <option value="80" {$stlib->selectVal('80', (string)$show_cnt)}>80</option>
          </select></p>
        </div>
        <dd>
          <div class="js-scrollable">
            <table cellspacing="0" cellpadding="0" style="border: 0">
              <tr>
                <th>日付</th>
                <th>名前</th>
                <th>操作</th>
                <th>ツールチップ</th>
              </tr>
              <tbody id="sortable">
                <tr>
                  <td>2024/01/10</td>
                  <td>山田太郎</td>
                  <td>
                    <a href="#" class="link">編集</a>
                    <a href="#" class="link">削除</a>
                  </td>
                  <td>
                    <ul class="iconList">
                      <li class="tooltipArea">
                        <a href="">
                          <img src="img/edit.svg" alt="編集">
                        </a>
                        <span class="toolTip">編集</span>
                      </li>
                      <li class="tooltipArea">
                        <a href="" class="del">
                          <img src="img/del.svg" alt="削除">
                        </a>
                        <span class="toolTip">削除</span>
                      </li>
                      <li class="tooltipArea">
                        <a href="">
                          <img src="img/copy.svg" alt="コピー">
                        </a>
                        <span class="toolTip">コピー</span>
                      </li>
                    </ul>
                  </td>
                </tr>
                <tr>
                  <td>2024/01/10</td>
                  <td>田中太郎</td>
                  <td>
                    <a href="#" class="link">編集</a>
                    <a href="#" class="link">削除</a>
                  </td>
                  <td>
                    <ul class="iconList">
                      <li class="tooltipArea">
                        <a href="">
                          <img src="img/edit.svg" alt="編集">
                        </a>
                        <span class="toolTip">編集</span>
                      </li>
                      <li class="tooltipArea">
                        <a href="" class="del">
                          <img src="img/del.svg" alt="削除">
                        </a>
                        <span class="toolTip">削除</span>
                      </li>
                      <li class="tooltipArea">
                        <a href="">
                          <img src="img/copy.svg" alt="コピー">
                        </a>
                        <span class="toolTip">コピー</span>
                      </li>
                    </ul>
                  </td>
                </tr>
              </tbody>
            </table><br>
            <p class="center">並び替えを行う場合は、上記の行をドラッグして移動後、確定ボタンを押してください。</p>
            <div class="center"><input type="submit" class="submit" value="並び替えを確定する" onclick="loading()"></div>
          </div>
          <input type="hidden" name="sort" id="sort">
        </dd>
      </form>
      <dt>詳細ページ</dt>
      <dd>
        <div class="detail">
          <p class="info">情報詳細</p>
          <div class="row">
            <p class="title">日付</p>
            <p class="content">2024/01/10</p>
          </div>
          <div class="row">
            <p class="title">名前</p>
            <p class="content">山田太郎</p>
          </div>
          <div class="row">
            <p class="title">名前（フリガナ）</p>
            <p class="content">ヤマダタロウ</p>
          </div>
        </div>
      </dd>
      <dt>ファイルアップロード</dt>
      <form action="parts_upload.php" method="post" enctype="multipart/form-data">
        <dd>
          <!-- <input type="file" name="file" id=""> -->
          <br><br>
          <input type="text" name="test"><br><br>
          <div class="dropzone w20" id="dropzone">
        </dd>
        <input type="submit" class="submit" value="送信">
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
  <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="./css/dropzone.css">
HTML;

$js = <<< "HTML"
  <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
  <script src="./js/dropzone-setting.js"></script>
HTML;

$script = <<< "JS"
  $('select[name="show_cnt"]').change(function(){
    const showCnt = $(this).val();
    $('input[name="cnt"]').val(showCnt);
    $('form').submit();
  });
JS;

read_template($css, $js, $script, $list);