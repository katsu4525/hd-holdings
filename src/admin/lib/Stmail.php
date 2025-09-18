<?php

/**
 * @file Stmail.php
 * @brief メールクラス
 * @date 2024-09-26
 *
 * Copyright isis Co.,ltd.
 */

namespace Stmail\Stmail;

// 公式通り
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Stlib\Stlib\Stlib;

//ソースを全部読み込ませる
require_once(__DIR__ . "/mailsend/PHPMailer-master/src/PHPMailer.php");
require_once(__DIR__ . "/mailsend/PHPMailer-master/src/SMTP.php");
// require_once(__DIR__ . "/mailsend/PHPMailer-master/src/POP3.php");
require_once(__DIR__ . "/mailsend/PHPMailer-master/src/Exception.php");
// require_once(__DIR__ . "/mailsend/PHPMailer-master/src/OAuth.php");
require_once(__DIR__ . "/mailsend/PHPMailer-master/language/phpmailer.lang-ja.php");

mb_language("ja");
mb_internal_encoding("UTF-8");

class Stmail
{
  private $host;      // SMTPサーバー
  private $charset;   // 文字コード
  private $user;      // SMTPユーザー名
  private $setDb;     // ログDB有無
  private $password;  // SMTPパスワード
  private $port;      // ポート (587:tls)

  private $from;      // 送信元メールアドレス
  private $fromname;  // 送信者名

  /**
   * コンストラクタ
   *
   * @param string $from  送信元メールアドレス
   * @param string $fromname  送信者名
   * @param boolean $setDb  ログDB有無
   * @param string $charset   文字コード
   * @param string $boolean   ログDB有無
   * @param string $host  SMTPサーバー
   * @param string $user  SMTPユーザー名
   * @param string $password  SMTPパスワード
   * @param string $port  ポート
   */
  public function __construct(
    string $from,
    string $fromname,
    bool $setDb = true,
    string $charset = "UTF-8",
    // string $host = "smtp.kagoya.net",
    // string $user = "isissys.noreply-melee",
    // string $password = "v3C5EaSG%",
    string $host = "",
    string $user = "",
    string $password = "",
    string $port = "587"
  ) {
    $this->from = $from;
    $this->fromname = $fromname;
    $this->setDb = $setDb;
    $this->charset = $charset;

    if (DF_LOCAL){
      $this->host = '';
      $this->user = '';
      $this->password = '';
    } else {
      $this->host = $host;
      $this->user = $user;
      $this->password = $password;
    }
    
    $this->port = $port;
  }

  /**
   * メール送信
   *
   * @param string $subject
   * @param boolean $htmlmail
   * @param string $body
   * @param array $to
   * @param array|null $cc
   * @param array|null $bcc
   * @param string|null $return
   * @param array|null $attach
   * @return boolean
   */
  public function sendMail(string $subject, bool $htmlmail, string $body, array $to, array $cc = null, array $bcc = null, string $return = null, array $attach = null): bool
  {
    require_once(__DIR__ . '/../common/common_setting.php');
    require_once(__DIR__ . '/Stlib.php');

    global $db;
    global $DF_ENCRYPTION_KEY;

    $mail_result = false;
    $tdate = date('Ymd');
    $logfile = __DIR__ . "/maillog/mail_{$tdate}.log";
    try {
      $mailer = new PHPMailer(true); //インスタンス生成
      $mailer->CharSet = $this->charset; //文字セットこれでOK
      if ($this->host != "" && $this->user != "" && $this->password != "") {
        $mailer->IsSMTP(); //SMTPを作成
        $mailer->Host = $this->host; //mailを使うのでメールの環境に合わせてね
        $mailer->CharSet = $this->charset; //文字セットこれでOK
        $mailer->SMTPAuth = true; //SMTP認証を有効にする
        $mailer->Username = $this->user; // mailのユーザー名
        $mailer->Password = $this->password; // mailのパスワード
        $mailer->SMTPSecure = 'LOGIN'; //SSLも使えると公式で言ってます
        $mailer->Port = $this->port; //tlsは587でOK
        // $mailer->SMTPDebug = 2; // 詳細デバッグ
      }

      $mailer->From     = $this->from; //差出人の設定
      $mailer->FromName = mb_convert_encoding($this->fromname, "UTF-8", "AUTO"); //表示名おまじない付…
      $mailer->Sender = $this->from; // Return-path

      $mailer->clearAddresses();  // 送信先初期化
      $mailer->clearAttachments();    // 添付ファイル初期化
      $mailer->clearCCs();    // CCクリア
      $mailer->clearBCCs();   // BCCクリア
      $mailer->Subject = mb_convert_encoding($subject, "UTF-8", "AUTO"); //件名の設定
      $mailer->Body = mb_convert_encoding($body, "UTF-8", "AUTO"); //メッセージ本体
      if ($htmlmail != false) {
        $mailer->isHTML(true);   // HTML形式を指定
      }

      // 宛先設定(to)
      if (empty($to)) {
        return false;
      }
      foreach ($to as $value) {
        $mailer->AddAddress($value); // To宛先
      }
      // 宛先設定(cc)
      if (!is_null($cc)) {
        foreach ($cc as $value) {
          $mailer->addCC($value);
        }
      }
      // 宛先設定(bcc)
      if (!is_null($bcc)) {
        foreach ($bcc as $value) {
          $mailer->addBCC($value);
        }
      }
      // Return-path(SenderとなるのでSendフォルダに入る可能性大)
      if (!empty($return)) {
        $mailer->Sender = $return; // Return-path設定
      }
      // 添付ファイル
      if (!is_null($attach)) {
        foreach ($attach as $value) {
          $mailer->addAttachment($value); // 添付
        }
      }
      $mail_result = $mailer->send();
    } catch (Exception $e) {
      file_put_contents($logfile, date("Y-m-d H:i:s") . ":{$subject}:{$to[0]}:error\n", FILE_APPEND);
      file_put_contents($logfile, "Message could not be sent. Mailer Error: {$mailer->ErrorInfo}\n", FILE_APPEND);

      $mail_result = false;   // 失敗
    }

    // ログ出力
    if ($this->setDb === true) {
      if ($db->existTable('sendmail_tb')) {
        $st = new Stlib();
        try {
          // $to_db = '';
          // if (empty($to)) {
          $to_db = json_encode($to, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
          // }
          $cc_db = '';
          if (empty($cc)) {
            $cc_db = json_encode($cc, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
          }
          $bcc_db = '';
          if (empty($bcc)) {
            $bcc_db = json_encode($bcc, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
          }
          $vecter = array_fill(0, 8, '');
          $db->setTransaction();
          $sql = 'INSERT INTO sendmail_tb (sm_frommail, sm_fromname, sm_to, sm_cc, sm_bcc, sm_subject, sm_body, sm_result, sm_vector1, sm_vector2, sm_vector3, sm_vector4, sm_vector5, sm_vector6, sm_vector7) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
          $bind = array();
          $bind[] = $st->sencript($this->from, $DF_ENCRYPTION_KEY, $vecter[0]);
          $bind[] = $st->sencript($this->fromname, $DF_ENCRYPTION_KEY, $vecter[1]);
          $bind[] = $st->sencript($to_db, $DF_ENCRYPTION_KEY, $vecter[2]);
          $bind[] = $st->sencript($cc_db, $DF_ENCRYPTION_KEY, $vecter[3]);
          $bind[] = $st->sencript($bcc_db, $DF_ENCRYPTION_KEY, $vecter[4]);
          $bind[] = $st->sencript($subject, $DF_ENCRYPTION_KEY, $vecter[5]);
          $bind[] = $st->sencript($body, $DF_ENCRYPTION_KEY, $vecter[6]);
          $bind[] = $mail_result === true ? 1 : 0;
          $bind[] = $vecter[0];
          $bind[] = $vecter[1];
          $bind[] = $vecter[2];
          $bind[] = $vecter[3];
          $bind[] = $vecter[4];
          $bind[] = $vecter[5];
          $bind[] = $vecter[6];
          $db->setSql($sql, $bind);

          $db->comit();
        } catch (\Exception $ex) {
          $db->rollback();
          recordLog($ex);
          file_put_contents($logfile, date("Y-m-d H:i:s") . ":mail db writer :error\n", FILE_APPEND);
        }
      }
    }
    return $mail_result;
  }
}
