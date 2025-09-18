<?php

/**
 * @file re_passup.php
 * @brief パスワード変更予約完了画面
 * @date 2023-08-29
 *
 * Copyright isis Co.,ltd.
 */

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/Stmail.php");

$new = filter_input(INPUT_POST, 'new');
$conf = filter_input(INPUT_POST, 'conf');
$am_key = filter_input(INPUT_POST, 'am', FILTER_SANITIZE_SPECIAL_CHARS);

if ($new !== $conf){
  header("Location: re_passend.php?ER=100");
  exit;
}

$ref = $_SERVER['HTTP_REFERER'];

try {
  // 予約テーブルの認証コードとリファーを照らし合わせる
  $sql = "SELECT * FROM supass_tb WHERE sp_admin_key = ?";
  $bind = array();
  $bind[] = $am_key;
  $res = $db->setSql($sql, $bind, PDO::FETCH_ASSOC, "fetch");

  // if ($ref != DF_HOST . "admin/re_passsetting.php?uq={$res['sp_uniq1']}&ud={$res['sp_uniq2']}&am={$am_key}") {
  //   header("Location: re_passend.php?ER=100");
  //   exit;
  // }

  // パスワード更新
  $sql = "UPDATE admin_tb SET am_pass = ? WHERE am_key = ?";
  $bind = array();
  $bind[] = password_hash($new, PASSWORD_DEFAULT);
  $bind[] = $am_key;
  $db->setSql($sql, $bind);

  // 変更予約削除
  $sql = "DELETE FROM supass_tb WHERE sp_admin_key = ?";
  $bind = array();
  $bind[] = $am_key;
  $db->setSql($sql, $bind);

} catch(Exception $e){
  // echo $e->getMessage();
  recordLog($e);
  header("Location: re_passend.php?ER=100");
  exit;
}

header("Location: re_passend.php?ER=0");
exit;
