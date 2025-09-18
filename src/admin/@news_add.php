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
$upload_temp_dir = './newsfile/temp/';
$upload_dir = './newsfile/';

$input_name = [];
$input_name[] = 'created';    // 作成日
$input_name[] = 'is_public';  // 公開設定
$input_name[] = 'cate';       // カテゴリー
$input_name[] = 'title';      // タイトル
$input_name[] = 'content';    // 内容
$input_name[] = 'main_file';   // メイン画像
$input_name[] = 'sub_files';   // サブ画像

$post = $stlib->getPost($input_name);

try {
  $sql = 'INSERT INTO news_tb (ne_created, ne_is_public, ne_cate, ne_title, ne_content) VALUES (?, ?, ?, ?, ?)';
  $bind = [];
  $bind[] = $post['created'];
  $bind[] = $post['is_public'];
  $bind[] = $post['cate'];
  $bind[] = $post['title'];
  $bind[] = $post['content'];
  $db->setSql($sql, $bind);

  $line = $db->getLine();

  if (isset($post['main_file'])){
    // メイン画像を一時保存フォルダから移動
    @rename($upload_temp_dir . $post['main_file']['saveName'], $upload_dir . $post['main_file']['saveName']);
    $sql = 'INSERT INTO newsfiles_tb (nf_is_main, nf_news_key, nf_savename, nf_originalname, nf_size) VALUES (?, ?, ?, ?, ?)';
    $bind = [];
    $bind[] = 1;
    $bind[] = $line;
    $bind[] = $post['main_file']['saveName'];
    $bind[] = $post['main_file']['originalName'];
    $bind[] = $post['main_file']['size'];
    $db->setSql($sql, $bind);
  }

  if (isset($post['sub_files'])){
    foreach ($post['sub_files'] as $key => $val){
      // サブ画像を一時保存フォルダから移動
      @rename($upload_temp_dir . $val['saveName'], $upload_dir . $val['saveName']);
      $sql = 'INSERT INTO newsfiles_tb (nf_is_main, nf_news_key, nf_savename, nf_originalname, nf_size) VALUES (?, ?, ?, ?, ?)';
      $bind = [];
      $bind[] = 2;
      $bind[] = $line;
      $bind[] = $val['saveName'];
      $bind[] = $val['originalName'];
      $bind[] = $val['size'];
      $db->setSql($sql, $bind);
    }
  }

  header("Location: news-list.php?MS=0");
} catch (Exception $e) {
  recordLog($e);
  header("Location: news.php?MS=100");
  exit;
}