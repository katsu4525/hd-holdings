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

  foreach ((array)$news_data as $val){
    $is_public = ((int)$val['ne_is_public'] === 1) ? '公開' : '非公開';
    // $title = (empty($val['ne_url'])) ? $val['ne_title'] : "<a href='{$val['ne_url']}' target='_blank' rel='noopener noreferrer'>{$val['ne_title']}</a>";
    $val['ne_content'] = nl2br($val['ne_content']);
    $content = (empty($val['ne_url'])) ? $val['ne_content'] : "<a href='{$val['ne_url']}' target='_blank' rel='noopener noreferrer'>{$val['ne_content']}</a>";

    $news_list .= <<< "HTML"
      <tr>
        <td>{$val['ne_created']}</td>
        <td>{$content}</td>
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
            <th>登録日</th>
            <th>タイトル</th>
            <th>公開設定</th>
            <th>カテゴリー</th>
            <th>操作</th>
          </tr>
          <tbody>
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