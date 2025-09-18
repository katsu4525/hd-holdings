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
$upload_dir = './newsfile/';

$input_name = [];
$input_name[] = 'ne'; // 記事キー  

$get = $stlib->getGET($input_name);

try {
  // 記事に紐づく画像データを取得
  $sql = 'SELECT * FROM newsfiles_tb WHERE nf_news_key = ?';
  $bind = [];
  $bind[] = $get['ne'];
  $file_data = $db->setSql($sql, $bind);

  // 画像ファイルを削除
  foreach ((array)$file_data as $file){
    @unlink($upload_dir . $file['nf_savename']);
  }

  // 画像データを削除
  $sql = 'DELETE FROM newsfiles_tb WHERE nf_news_key = ?';
  $bind = [];
  $bind[] = $get['ne'];
  $db->setSql($sql, $bind);

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