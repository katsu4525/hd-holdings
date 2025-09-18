<?php

/************************************
 ** first.php
 ** ログイン初期画面
 ***********************************/
require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");
chk_session(DF_SES_NAM);

require_once(__DIR__ . "/temple.php");
require_once(__DIR__ . "/lib/message.php");

// ユーザーメール登録済みか確認
// $sql = "SELECT * FROM user_tb WHERE user_key = 1";  // 1つ目は必ず管理者とすること
// $ret_um = $db->setSql($sql);
$email = 0;
// if ($ret_um[0]['user_mail'] == "") {
//     $email = 1; // メールが空
// }

$script = "";
// if (DF_USERMNG == true) {
//     $script = <<< "JS"
//     // ユーザー情報登録促し
//     if({$email}){
//         if(confirm('管理者情報が登録されていません。\\n登録してください。')){
//             location.href='user_list.php';
//         }
//     }
// JS;
// } else {
//     $script = <<< "JS"
//     // ユーザー情報登録促し
//     if({$email}){
//         if(confirm('ユーザー情報が登録されていません。\\n登録してください。')){
//             location.href='user.php';
//         }
//     }
// JS;
// }


$info = "";
// if ($_SESSION['auth'] == 1) {
//     $np = "";
//     if (DF_CART != false) {
//         $np = "+";
//     }
//     $sql = "SELECT * FROM version_tb";
//     $ret_v = $db->setSql($sql);
//     if (empty($ret_v)) {
//         $dbversion = '-';
//     } else {
//         $dbversion = $ret_v[0]['vs_db'];
//     }
//     $php = phpversion();
//     $mysql = $db->getVersion();
//     // $mysql = $db->$pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
//     $info = <<< "HTML"
// Nolosee{$np} Version: {$VERSION}<br>
// DB Version: {$dbversion}<br>
// Host IP: {$_SERVER['SERVER_ADDR']}<br>
// PHP Version: {$php}<br>
// MySQL Version: {$mysql}<br>
// <br>
// <span style="color:red;">スーパー管理者権限でログインしています。操作時は十分注意してください。</span>
// <br>
// <br>
// HTML;
// }

// バックアップデータの削除
// array_map('unlink', glob("backupdata/*.sql"));
// array_map('unlink', glob("backupdata/*.zip"));
// array_map('unlink', glob("backupdata/*.csv"));

// ecordertmp_tb掃除
// $sql = "DELETE FROM ecordertmp_tb WHERE ec_date < ( NOW( ) - INTERVAL 2 DAY  )";
// $db->setSql($sql);

// 本文
$list = <<< "HTML"
  <p class="first">左のメニューよりお選びください。</p><br>{$message_dialog}<br><br>{$info}
HTML;

$css = <<< "HTML"
  <style>
    .first {
      margin: 30px 0 0 160px;
    }
  </style>
HTML;

read_template($css, "", $script, $list);
