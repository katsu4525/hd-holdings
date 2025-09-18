<?php

/**
 * @file login_chk.php
 * @brief 管理者ログインチェック
 * @date 2022-08-18
 *
 * Copyright isis Co.,ltd.
 */

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");

$logid_code = filter_input(INPUT_POST, 'LoginID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$logid_pass = filter_input(INPUT_POST, 'PassWord', FILTER_DEFAULT);
// $logid_pass = filter_input(INPUT_POST, 'PassWord', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if ($logid_code == null || $logid_pass == null) {
  header("Location: login.php?ER=1");
  exit;
}

$sql = "SELECT * FROM admin_tb WHERE am_id = BINARY ?";
$bind = array();
$bind[] = $logid_code;
$res_user = $db->setSql($sql, $bind, PDO::FETCH_ASSOC, "fetch");

// ユーザ無し
if (empty($res_user)) {
  header("Location: login.php?ER=2");
  exit;
}

$now = new DateTime();
$now = $now->format('Y-m-d H:i:s');

$unlock = new DateTime($res_user['am_allowtime']);
$unlock = $unlock->format('Y-m-d H:i:s');

if ((int)$res_user['am_ecnt'] >= 6 && $now < $unlock){
  // ロック中
  header("Location: gauthentication_lock.html");
  exit;
}

if (password_verify($logid_pass, $res_user["am_pass"])){
  // パスワード一致

  // ロック解除
  $sql = "UPDATE admin_tb SET am_ecnt = 0, am_allowtime = '' WHERE am_key = ?";
  $bind = [];
  $bind[] = $res_user['am_key'];
  $db->setSql($sql, $bind);
  
  if ($DF_2STEP_CHECK){
    // 2段階認証あり
    session_start();
    $_SESSION['logkey'] = $res_user["am_key"];
    $_SESSION['logid'] = $res_user["am_id"];

    header("Location: gauthentication.php");
    exit;
  } else {
    // 2段階認証なし
    mysession_start(DF_SES_NAM);  // セッションスタート
    $last_time = new DateTime($res_user["am_logintime"]);
    $last_time = $last_time->format('Y / m / d　H:i');

    $_SESSION['logkey'] = $res_user["am_key"];
    $_SESSION['auth'] = true;
    $_SESSION['logid'] = $res_user["am_id"];
    // $_SESSION['lasttime'] = $res_user["am_logintime"];
    $_SESSION['lasttime'] = $last_time;
  
    $sql = "UPDATE admin_tb SET am_logintime = ? WHERE am_key = ?";
    $bind = array();
    $bind[] = date('Y-m-d H:i:s');
    $bind[] = $res_user["am_key"];
    $db->setSql($sql, $bind);

    session_regenerate_id(true);

    header("Location: first.php");
    exit;
  }
} else {
  // パスワード不一致
  header("Location: login.php?ER=3");
  exit;
}