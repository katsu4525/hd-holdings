<?php

/**
 * @file news-list.php
 * @brief 記事一覧
 * @date 2025-09-10
 *
 * Copyright isis Co.,ltd.
 */

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");
chk_session(DF_SES_NAM);

require_once(__DIR__ . "/temple.php");
require_once(__DIR__ . "/lib/message.php");

$news_list = '';

try {
  $sql = "SELECT * FROM news_tb ORDER BY ne_created DESC, ne_key DESC";
  $news_data = $db->setSql($sql);

  $sql = "SELECT * FROM newsfiles_tb WHERE nf_is_main = 1";
  $main_image_data = $db->setSql($sql);

  // 事前に画像データを連想配列化
  $main_image_map = [];
  foreach ((array)$main_image_data as $image) {
    $main_image_map[$image['nf_news_key']] = $image['nf_savename'];
  }

  foreach ((array)$news_data as $val){
    // 連想配列から高速に取得
    $main_image = isset($main_image_map[$val['ne_key']]) ? $main_image_map[$val['ne_key']] : '';
    $img_tag = (!empty($main_image)) ? "<img src='newsfile/{$main_image}' alt=''>" : "";
    $is_public = ((int)$val['ne_is_public'] === 1) ? '公開' : '非公開';

    $news_list .= <<< "HTML"
      <tr>
        <td class="image_area">{$img_tag}</td>
        <td>{$val['ne_created']}</td>
        <td>{$val['ne_title']}</td>
        <td>{$is_public}</td>
        <td>{$val['ne_cate']}</td>
        <td>
          <a href="news-edit.php?ne={$val['ne_key']}" class="link">編集</a>
          <a href="news_del.php?ne={$val['ne_key']}" class="pweit del">削除</a>
        </td>
      </tr>
    HTML;
  }
} catch (Exception $e) {
  recordLog($e);
  header("Location: first.php?MS=100");
  exit;
}

$list = <<< "HTML"
  <div class="pankuzu">
    <p>記事管理</p>
    <span>></span>
    <p>記事一覧</p>
  </div>
  <h2 style="display:flex">
    <img src="img/icon02.png" width="30" height="30">
    記事一覧
  </h2>
  {$message_dialog}<br>
  <div class="reg">
    <dl>
      <dt>記事一覧</dt>
      <dd>
        <table cellspacing="0" cellpadding="0" style="border: 0">
          <tr>
            <th>メイン画像</th>
            <th>登録日</th>
            <th>タイトル</th>
            <th>公開設定</th>
            <th>カテゴリー</th>
            <th>操作</th>
          </tr>
          <tbody id="sortable">
            <tr>
              {$news_list}
            </tr>
          </tbody>
        </table>
      </dd>
    </dl>
  </div>
HTML;

$css = <<< "HTML"
  <style>
    .image_area img {
      max-height: 80px;
      object-fit: cover;
    }
    #mainArea table .link{
      margin-right: 8px;
    }
  </style>
HTML;

$js = <<< "HTML"
HTML;

$script = <<< "JS"
  $('.del').on('click', async function(e){
    e.preventDefault();

    if (await confdialog('記事を削除します。よろしいですか？')){
      loading();
      location.href = $(this).attr('href');
    }
  });
JS;

read_template($css, $js, $script, $list);