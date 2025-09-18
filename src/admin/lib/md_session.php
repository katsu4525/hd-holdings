<?php

define("DF_EXPIRES_SES", 10);   // sec
define("DF_SESSION_CUT_URL", "logout.php"); // sessionエラー時の処理をするURL
define("ADMIN_TOP", "startpage.php");

// セッション名
define("DF_SES_NAM", "test");
/*------------------------------------------------------
    セッション　スタート
    $type: セッション確認用変数名
------------------------------------------------------*/
function mysession_start($type)
{
    global $DF_DEBUG;
    //SSLは、第4引数にSSLを指定。うまくいかない場合はini_setで指定

    //PHP5.5以上でsession.use_strict_modeを1とした場合下記追加
    if (!empty($_SESSION['deleted_time']) && $_SESSION['deleted_time'] < time() - 180) {
        session_destroy();
    }

    if ($DF_DEBUG) {
        session_set_cookie_params(0);   //テスト環境ではこちら
    } else {
        // session_set_cookie_params(0);   //テスト環境ではこちら
        session_set_cookie_params(0, '/', '', DF_SSL_ONOFF, true);  // ※セッションが切れることが多い
    }
    session_start();
    $_SESSION[$type] = get_fingerprint();
}


/*-------------------------------------------------------
    session_regenerate_idによるセッションの切断を防ぐ
-------------------------------------------------------*/
function mysession_regenerate_id()
{

	if (true) {
 		if (strpos($_SERVER['SCRIPT_FILENAME'],ADMIN_TOP)===FALSE) {	//topは遷移してすぐなので切れるため除外
			session_regenerate_id(true);
		}
	} else {
		//現行のIDをいったん退避
		// $old_session_id = session_id();
        // $_SESSION['old_session_id'] = $old_session_id;

		// 不安定なネットワークのために、セッションID が設定されなかったときは、
		// 新しいセッションID が、適切なセッションIDに設定されることが必須。
		if (function_exists('session_create_id')) {
			$new_session_id = session_create_id();	
		} else {
			session_regenerate_id();	//新しいIDを発行
			$new_session_id = session_id();
		}
		$_SESSION['new_session_id'] = $new_session_id;

		// 破棄された時のタイムスタンプを設定
		$_SESSION['keika'] = time();

		// 現在のセッションを書き込んで閉じる
		session_commit();

		// 新しいセッションを新しいセッションIDで開始
		session_id($new_session_id);
		// session_start();	//再度スタートさせる必要あり

		// 新しいセッションには、以下の情報は不要
		unset($_SESSION['new_session_id']);
		unset($_SESSION['keika']);	//ここで本来破棄

		usleep(5000);	//セッションの処理が終わるまで待つ
	}
}


/*-------------------------------------------------------
    セッションの確認
    $type セッション開始時に指定したセッション確認用変数名
-------------------------------------------------------*/
function chk_session($type)
{
    global $DF_APP_NAME;
    //セッションのスタート
    // mysession_start($type);
    session_start();
    // mysession_start(DF_SSL_ONOFF);
    //IDの振替
    mysession_regenerate_id();

    $str = get_fingerprint();
    // if ($_SESSION[$type]=="" && empty($_SESSION[ID])) {
    if (empty($_SESSION[$type])) {
        // if ($_SESSION[$type] == "") {
        //sessionが切れた
        header("Location: " . DF_SESSION_CUT_URL . "?ER=100");  // mysession_destroy()処理をするページへ飛ばす
        exit;
    } elseif ($str !== $_SESSION[$type]) {    // セッションハイジャックを検出
        $ip = getenv("REMOTE_ADDR");
        $host = getenv("REMOTE_HOST");
        if ($host == null || $host == $ip) {
            $host = gethostbyaddr($ip);
        }

        // ログの書き出し、エラー処理
        $msg = "session jack " . $type . " in " . $DF_APP_NAME .
            "\nIPaddress: " . $ip . "\nhost: " . $host;
        error_log($msg, 1, "suzuki@wins-lb.org");
        //今あるセッションを破棄
        mysession_destroy();
        header("Location: " . DF_SESSION_CUT_URL . "?ER=200");  // mysession_destroy()処理をするページへ飛ばす
        exit;
    }
}


/*------------------------------------------------------
    security check
------------------------------------------------------*/
function get_fingerprint()
{
    global $DF_APP_NAME;
    // 何か適当な秘密の文字列(推測できないもの)
    $fingerprint = $DF_APP_NAME . '_adm';

    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $fingerprint .= $_SERVER['HTTP_USER_AGENT'];
    }
    if (!empty($_SERVER['HTTP_ACCEPT_CHARSET'])) {
        $fingerprint .= $_SERVER['HTTP_ACCEPT_CHARSET'];
    }

    return sha1($fingerprint);
}


// ◎セッション破棄について
// ログインに失敗した場合など、セッションは必ず終了させることが必要
// また、ログアウトなどでは、下記モジュールを呼び出す

/*-------------------------------------------------------
    セッションの破棄
-------------------------------------------------------*/
function mysession_destroy()
{
    //session_name("something")を使用している場合は特にこれを忘れないように!
    if (!isset($_SESSION)){
      session_start();
    }

    // セッション変数を全て解除する
    $_SESSION = array();

    // セッションを切断するにはセッションクッキーも削除する。
    if (ini_get('session.use_cookies')) {
        // セッション クッキーを削除
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $params['path']);
    }
    // 最終的に、セッションを破壊する
    session_destroy();
}
