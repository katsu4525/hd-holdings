<?php

/**
 * @file news_add.php
 * @brief 記事登録
 * @date 2025-09-03
 *
 * Copyright isis Co.,ltd.
 */

use Stlib\Stlib\Stlib;

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");
chk_session(DF_SES_NAM);
require_once(__DIR__ . "/lib/Stlib.php");

$stlib = new Stlib();

$input_name = [];
$input_name[] = 'created';    // 作成日
$input_name[] = 'is_public';  // 公開設定
$input_name[] = 'cate';       // カテゴリー
$input_name[] = 'title';      // タイトル
$input_name[] = 'url';        // リンクURL
$input_name[] = 'ne';   // 記事キー

$post = $stlib->getPost($input_name);

try {
  $sql = 'UPDATE news_tb SET ne_created = ?, ne_is_public = ?, ne_cate = ?, ne_title = ?, ne_url = ? WHERE ne_key = ?';
  $bind = [];
  $bind[] = $post['created'];
  $bind[] = $post['is_public'];
  $bind[] = $post['cate'];
  $bind[] = $post['title'];
  $bind[] = $post['url'];
  $bind[] = $post['ne'];
  $db->setSql($sql, $bind);

  header("Location: news-list.php?MS=1");
} catch (Exception $e) {
  recordLog($e);
  header("Location: news.php?MS=100");
  exit;
}