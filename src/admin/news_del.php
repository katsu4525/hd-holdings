<?php

/**
 * @file news_del.php
 * @brief 記事削除
 * @date 2025-09-11
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
$input_name[] = 'ne'; // 記事キー  

$get = $stlib->getGET($input_name);

try {
  // 記事データを削除
  $sql = 'DELETE FROM news_tb WHERE ne_key = ?';
  $bind = [];
  $bind[] = $get['ne'];
  $db->setSql($sql, $bind);

  header("Location: news-list.php?MS=2");
} catch (Exception $e) {
  recordLog($e);
  header("Location: news.php?MS=100");
  exit;
}