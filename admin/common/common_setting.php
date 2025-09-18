<?php

/**
 * @file common_setting.php
 * @brief 簡単な説明
 * @date 2023-03-07
 *
 * Copyright isis Co.,ltd.
 */

use Stdb\Stdb\Stdb;

date_default_timezone_set('Asia/Tokyo');

require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once(__DIR__ . '/../lib/Stdb.php');

// 環境変数読み込み
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../setting/');
$dotenv->load();
extract($_ENV, EXTR_PREFIX_INVALID, 'ERRENV');

if ($DF_DEBUG) {
  ini_set('display_errors', 1);
} else {
  ini_set('display_errors', 0);
}
error_reporting(E_ALL);
if ($DF_ERRLOG_CYCLE == 1) {
  // 毎日ログ出力
  $loddate = date('Ymd');
} else {
  // 毎月ログ出力
  $loddate = date('Ym');
}
ini_set("error_log", __DIR__ . "/errorlog/php_error-{$loddate}.log");

// Webサーバー用設定
if (isset($_SERVER["HTTP_HOST"])) {
  //PHP設定
  ini_set('session.cookie_httponly', 1);
  ini_set('session.hash_function', 1);

  if (strpos($_SERVER['SCRIPT_FILENAME'], "admin") !== false) {
    ini_set('session.name', $DF_SESSION_NAME . '_user0124');   //名前は自由数字のみはアウト
  } else {
    ini_set('session.name', $DF_SESSION_NAME . '_other5548'); //名前は自由数字のみはアウト
  }
  ini_set('session.use_strict_mode', 1);  //5.5以上

  //ヘッダ出力
  header("X-XSS-Protection: 1; mode=block");
  header('X-Frame-Options: SAMEORIGIN');  // クリックジャッキング対策

  if (isset($_SERVER["HTTPS"])) {
    $ssl = true;
    $LOCAL = false;  // ローカル環境ならtrue
    $host_name = 'https://' . $DF_DOMAIN . '/';
  } else {
    $ssl = false;
    $LOCAL = true;  // ローカル環境ならtrue
    $host_name = 'http://localhost:8080/';
  }
  /**
   * 以下のdefineはcron環境では使用しないこと
   */
  define("DF_SSL_ONOFF", $ssl);  // SSL時”true”にする
  define("DF_LOCAL", $LOCAL);  // ローカル
  define("DF_HOST", $host_name);  // ルートURL
  // SSLサイトには下記必須
  if (DF_SSL_ONOFF) {
    ini_set('session.cookie_secure', 1);
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
  }
}

// 許可タグ
$ENABLE_TAGS = '<p><br><a><strong><em><u><span><sub><sup><blockquote>';

// DB接続
if ((mb_strpos(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '', "127.0.0.1") !== false)
  || (mb_strpos(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '', "localhost") !== false)
  || (mb_strpos(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '', "192.168.0.") !== false)
) {
  $dbuser = $DB_USER_LOCAL;
  $dbpass = $DB_PASS_LOCAL;
  $dbhost = $DB_HOST_LOCAL;
  $dbname = $DB_NAME_LOCAL;
  $dbport = empty($DB_PORT_LOCAL) ? null : $DB_PORT_LOCAL;
} else {
  $dbuser = $DB_USER;
  $dbpass = $DB_PASS;
  $dbhost = $DB_HOST;
  $dbname = $DB_NAME;
  $dbport = empty($DB_PORT) ? null : $DB_PORT;
}
$db = new Stdb();
$db->setConnect($dbname, $dbhost, $dbuser, $dbpass, $dbport);

// Fレジ
if ((mb_strpos($_SERVER['HTTP_HOST'], "127.0.0.1") !== false)
  || (mb_strpos($_SERVER['HTTP_HOST'], "localhost") !== false)
  || (mb_strpos($_SERVER['HTTP_HOST'], "192.168.0.") !== false)
) {
  // ローカル
  $fregi = 0;
} else {
  // 本番
  $fregi = $DF_FREGI; // 0:fレジ処理なし 1:fレジ処理テスト 2:fレジ処理本番 
}

/**
 * エラーログ出力
 *
 * @param string $str
 * @return void
 */
function recordLog(string $str)
{
  $dbg = debug_backtrace();
  $err = "{$_SERVER["REMOTE_ADDR"]} {$dbg[0]['file']}({$dbg[0]['line']}) {$str}";
  error_log($err, 0);
}

// 操作ログ記録
function log_record($commnet = '')
{
  global $db;

  $dbg = debug_backtrace();
  // POST値
  $postval = '';
  if (isset($_POST)) {
    $postval = json_encode($_POST, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  }
  // GET値
  $getval = '';
  if (isset($_GET)) {
    $getval = json_encode($_GET, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  }
  // SESSION値
  $sessionval = '';
  if (isset($_SESSION)) {
    $sessionval = json_encode($_SESSION, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  }

  try {
    $db->setTransaction();
    $sql = "INSERT INTO opelog_tb (ol_amid, ol_name, ol_opeurl, ol_comment, ol_idkey, ol_remote, ol_agent, ol_post, ol_get, ol_session)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $bind = array();
    $bind[] = isset($_SESSION['logid']) ? $_SESSION['logid'] : 'unknown id';
    $bind[] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'unknown name';
    $bind[] = "{$dbg[0]['file']}";
    $bind[] = $commnet;
    $bind[] = isset($_SESSION['logkey']) ? $_SESSION['logkey'] : 'unknown key';
    $bind[] = $_SERVER["REMOTE_ADDR"];
    $bind[] = $_SERVER['HTTP_USER_AGENT'];
    $bind[] = $postval;
    $bind[] = $getval;
    $bind[] = $sessionval;
    $db->setSql($sql, $bind);
    $db->comit();
  } catch (\Throwable $th) {
    //throw $th;
    $db->rollback();
    $er_msg = $th->getMessage();
    recordLog($er_msg);
  }
}

// POST最大サイズ確認
function checkPostSize(): bool
{
  if (!isset($_SERVER["CONTENT_LENGTH"])) {
    return false;
  }
  $postmsxsize = strtoupper(ini_get('post_max_size'));
  $maxsize = 0;
  if (strpos($postmsxsize, 'K') !== false) {
    // キロ
    $temp = substr($postmsxsize, 0, strpos($postmsxsize, 'K'));
    $maxsize = $temp * 1000;
  } elseif (strpos($postmsxsize, 'M') !== false) {
    // メガ
    $temp = substr($postmsxsize, 0, strpos($postmsxsize, 'M'));
    $maxsize = $temp * 1000 * 1000;
  } elseif (strpos($postmsxsize, 'G') !== false) {
    // ギガ
    $temp = substr($postmsxsize, 0, strpos($postmsxsize, 'G'));
    $maxsize = $temp * 1000 * 1000 * 1000;
  } else {
    // 単位なし
    $maxsize = $postmsxsize;
  }
  // サイズ確認
  if ($_SERVER["CONTENT_LENGTH"] > $maxsize) {
    return false;
  }

  return true;
}
