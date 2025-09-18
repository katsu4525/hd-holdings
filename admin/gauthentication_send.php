<?php

/**
 * @file gauthentication_send.php
 * @brief QRコード発行用メール送信
 * @date 2023-07-21
 *
 * Copyright isis Co.,ltd.
 */

use StMail\StMail\StMail;

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");
require_once(__DIR__ . "/lib/Stmail.php");

session_start();

if (empty($_SESSION['logkey'])){
  header("Location: gautentication_comp.php?ER=99");
  exit;
}

$am_key = $_SESSION['logkey'];
$am_id = $_SESSION['logid'];

$mail = filter_input(INPUT_POST, 'mail');

try{
  if ($am_id !== $DF_BACK_DOOR){
    // ユーザー情報
    $sql = "SELECT * FROM admin_tb WHERE am_id = BINARY ?";
    $bind = [];
    $bind[] = $mail;
    $res_user = $db->setSql($sql, $bind, PDO::FETCH_ASSOC, 'fetch');
  
    if (empty($res_user)){
      header("Location: gautentication_comp.php?ER=98");
      exit;
    }
  
    if ((int)$res_user['am_key'] !== (int)$am_key){
      header("Location: gautentication_comp.php?ER=97");
      exit;
    }
  }

  $uniq1 = sha1(uniqid(rand(), true));
  sleep(1);
  $uniq2 = sha1(uniqid(rand(), true));
  $limit = strtotime("+1800 second");

  // すでに変更テーブル予約が有れば削除
  $sql = "DELETE FROM suqr_tb WHERE sq_admin_key = ?";
  $bind = [];
  $bind[] = $am_key;
  $db->setSql($sql, $bind);

  // 新規予約
  $sql = "INSERT INTO suqr_tb (sq_admin_key, sq_uniq1, sq_uniq2, sq_limit) VALUE (?, ?, ?, ?)";
  $bind = [];
  $bind[] = $am_key;
  $bind[] = $uniq1;
  $bind[] = $uniq2;
  $bind[] = $limit;
  $db->setSql($sql, $bind);

} catch (Exception $e) {
  recordLog($e);
  header("Location: gautentication_comp.php?ER=99");
  exit;
}

/************************************
 ** メール送信処理
***********************************/

// url作成
$url = DF_HOST . "admin/gauthentication_qr.php?uq={$uniq1}&ud={$uniq2}&am={$am_key}";

// メール送信
$subject = "[管理画面] QRコードを発行しました。";
$to = array($mail);
$body = <<< "TEXT"
※※※　本メールにお心当たりがない場合は破棄ください。 ※※※

QRコードを発行しました。
下記URLにアクセスしてQRコードを読み込んでください。

{$url}


なおこのURLには期限があります。
30分以内に変更処理を完了してください。


------------------------------
※本メールは送信専用のメールで送信しており、ご返信いただいても回答いたしかねます。


TEXT;

$mail = new StMail($DF_NOREPLY, $DF_SYSTEM_NAME);
$mail->sendMail($subject, false, $body, $to);

header("Location: gauthentication_comp.php?ER=0");
exit;