<?php

/**
 * @file re_passfunc.php
 * @brief パスワード変更前処理
 * @date 2023-08-29
 *
 * Copyright isis Co.,ltd.
 */

use Stmail\Stmail\Stmail;

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/Stmail.php");

$LoginID = filter_input(INPUT_POST, 'LoginID');
if (!isset($LoginID)) {
  header("Location: re_passset.php?ER=100");
  exit;
}

// $Mail = filter_input(INPUT_POST, 'Mail');
// if (!isset($Mail)){
//   header("Location: re_passset.php?ER=100");
//   exit;
// }

$uniq1 = sha1(uniqid(rand(), true));
sleep(1);
$uniq2 = sha1(uniqid(rand(), true));
$limit = strtotime("+1800 second");

try {
  // 管理者取得
  $sql = "SELECT am_key FROM admin_tb WHERE am_id = ?";
  $bind = array();
  $bind[] = $LoginID;
  $am_key = $db->setSql($sql, $bind, PDO::FETCH_COLUMN, "fetch");

  if (!empty($am_key)){
    // すでに変更テーブル予約が有れば削除
    $sql = "DELETE FROM supass_tb WHERE sp_admin_key = ?";
    $bind = array();
    $bind[] = $am_key;
    $db->setSql($sql, $bind);
  
    // 新規予約
    $sql = "INSERT INTO supass_tb (sp_admin_key, sp_uniq1, sp_uniq2, sp_limit) VALUE (?, ?, ?, ?)";
    $bind = array();
    $bind[] = $am_key;
    $bind[] = $uniq1;
    $bind[] = $uniq2;
    $bind[] = $limit;
    $db->setSql($sql, $bind);
  } else {
    header("Location: re_pass.php?ER=1");
    exit;
  }
} catch(Exception $e){
  recordLog($e);
  header("Location: re_passend.php?ER=100");
  exit;
}

/************************************
 ** メール送信処理
 ***********************************/

// url作成
$url = DF_HOST . "admin/re_passsetting.php?uq={$uniq1}&ud={$uniq2}&am={$am_key}";


// メール送信
$subject = "[管理画面] パスワード変更を受け付けました。";
$to = array($LoginID);
$body = <<< "TEXT"
※※※　本メールにお心当たりがない場合は破棄ください。 ※※※

パスワードの変更を受け付けました。
下記URLにアクセスしてパスワードを変更してください。

{$url}


なおこのURLには期限があります。
30分以内に変更処理を完了してください。


------------------------------
※本メールは送信専用のメールで送信しており、ご返信いただいても回答いたしかねます。


TEXT;

$mail = new Stmail($DF_NOREPLY, $DF_SYSTEM_NAME);
$mail->sendMail($subject, false, $body, $to);


header("Location: re_passset.php?ER=0");
exit;
