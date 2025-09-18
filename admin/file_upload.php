<?php

/**
 * @file file_upload.php
 * @brief ファイルアップロード
 * @date 2025-09-03
 *
 * Copyright isis Co.,ltd.
 */

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");
chk_session(DF_SES_NAM);

function normalize_files_array($files) {
  $normalized = [];
  if (is_array($files['name'])) {
    // 複数ファイル
    $file_count = count($files['name']);
    for ($i = 0; $i < $file_count; $i++) {
      $normalized[] = [
        'name' => $files['name'][$i],
        'type' => $files['type'][$i],
        'tmp_name' => $files['tmp_name'][$i],
        'error' => $files['error'][$i],
        'size' => $files['size'][$i]
      ];
    }
  } else {
    // 単一ファイル
    $normalized[] = $files;
  }
  return $normalized;
}

if (isset($_FILES['mainfile'])){
  // メイン画像
  $files = normalize_files_array($_FILES['mainfile']);
}

if (isset($_FILES['subfiles'])){
  // サブ画像
  $files = normalize_files_array($_FILES['subfiles']);
}

$upload_temp_dir = './newsfile/temp/';
$upload_dir = './newsfile/';
$response = [];
$all_success = true;
$max_file_size = 10 * 1024 * 1024; // 10M
$allowed_extensions = ['jpg','jpeg','png','gif']; // 許可拡張子

foreach ($files as $key => $val){
  $tmp_name = $val['tmp_name'];
  $error = $val['error'];
  $ext = strtolower(pathinfo($val['name'], PATHINFO_EXTENSION));
  $size = $val['size'];

  if ($error !== UPLOAD_ERR_OK){
    // アップロードエラー
    $all_success = false;
    $response[] = ['file' => $val['name'], 'status' => 'error', 'message' => 'アップロード失敗'];
    continue;
  }

  if (!in_array($ext, $allowed_extensions, true)){
    // 拡張子エラー
    $all_success = false;
    $response[] = ['file' => $val['name'], 'status' => 'error', 'message' => '許可されていない拡張子です'];
    continue;
  }

  if ($size > $max_file_size){
    // サイズオーバー
    $all_success = false;
    $response[] = ['file' => $val['name'], 'status' => 'error', 'message' => 'ファイルサイズが大きすぎます'];
    continue;
  }

  $save_name = uniqid(mt_rand(), false) . '.' . $ext;
  // if ((int)$key === 1){
  //   $all_success = false;
  //   $response[] = ['file' => $val['name'], 'status' => 'error', 'message' => '保存に失敗'];
  // } else {

    if (move_uploaded_file($tmp_name, $upload_temp_dir . $save_name)) {
      $response[] = ['file' => $save_name, 'status' => 'success', 'message' => 'OK'];
    } else {
      $all_success = false;
      $response[] = ['file' => $val['name'], 'status' => 'error', 'message' => '保存に失敗'];
    }
  // }
}


// もし1つでも失敗があれば一時保存を削除
if (!$all_success) {
  // 失敗があれば一時保存したファイルを削除
  $temp_files = glob($upload_temp_dir . '/*');
  foreach ($temp_files as $file) {
    if (is_file($file)) {
      @unlink($file);
    }
  }
}

header('Content-Type: application/json');
echo json_encode($response);