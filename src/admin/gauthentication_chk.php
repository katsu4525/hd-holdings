<?php

/**
 * @file gauthentication_chk.php
 * @brief google認証コードチェック処理
 * @date 2023-07-21
 *
 * Copyright isis Co.,ltd.
 */

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");
require_once(__DIR__ . "/lib/GoogleAuthenticator.php");

session_start();

if (empty($_SESSION['logkey'])){
  header("Location: login.php?ER=99");
  exit;
}

$auth_code = filter_input(INPUT_POST, 'code');
if (empty($auth_code)){
  header("Location: login.php?ER=99");
  exit;
}

try {

  // ユーザー情報
  $sql = "SELECT * FROM admin_tb WHERE am_key = ?";
  $bind = [];
  $bind[] = $_SESSION['logkey'];
  $res_user = $db->setSql($sql, $bind, PDO::FETCH_ASSOC, 'fetch');

  if (empty($res_user)){
    header("Location: login.php?ER=99");
    exit;
  }

  $secret = $res_user['am_secret'];
  $ga = new PHPGangsta_GoogleAuthenticator();
  $discrepancy = 2; // サーバーとクライアントで許容する時間のずれ $discrepancy × 30秒
  $check_result = $ga->verifyCode($secret, $auth_code, $discrepancy);

  if ($check_result){
    // 認証OK

    // 1時的にセッションを切る
    $_SESSION = array();
    // セッションを切断するにはセッションクッキーも削除する。
    if (ini_get('session.use_cookies')) {
      // セッション クッキーを削除
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 3600, $params['path']);
    }
    // 最終的に、セッションを破壊する
    session_destroy();

    // 本番セッションスタート
    mysession_start(DF_SES_NAM);  // セッションスタート
    $last_time = new DateTime($res_user["am_logintime"]);
    $last_time = $last_time->format('Y / m / d　H:i');

    $_SESSION['logkey'] = $res_user["am_key"];
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

  } else {

    // エラーカウントアップ
    $sql = "UPDATE admin_tb SET am_ecnt = am_ecnt + 1, am_allowtime = ? WHERE am_key = ?";
    $bind = [];
    $bind[] = date('Y-m-d H:i:s');
    $bind[] = $_SESSION['logkey'];
    $db->setSql($sql, $bind);

    $sql = "SELECT am_ecnt FROM admin_tb WHERE am_key = ?";
    $bind = [];
    $bind[] = $_SESSION['logkey'];
    $am_ecnt = $db->setSql($sql, $bind, PDO::FETCH_COLUMN, 'fetch');

    if ((int)$am_ecnt >= 6){
      $nowcl = new DateTime();
      $nowcl->modify('+30 minute');
      $m30 = $nowcl->format('Y-m-d H:i:s');
      
      $sql = "UPDATE admin_tb SET am_allowtime = ? WHERE am_key = ?";
      $bind = [];
      $bind[] = $m30;
      $bind[] = $res_user["am_key"];
      $db->setSql($sql, $bind);

      header("Location: gauthentication_lock.php");
      exit;
    } else {
      header("Location: gauthentication.php?ER=1");
      exit;
    }
  }
} catch (Exception $e) {
  header("Location: login.php?ER=99");
  exit;
}