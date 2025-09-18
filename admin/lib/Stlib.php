<?php

namespace Stlib\Stlib;

use \DateTime;

/**
 * 基本ライブラリクラス
 */
class Stlib
{
    // const DF_HASH_COST = 10;

    /**
     * パスワードハッシュ作成
     *
     * @param string $password
     * @return string|false   // Hash
     */
    public function myPassHash($password)
    {
        // $options = array('cost' => DF_HASH_COST);
        /*ハッシュ化方式にPASSWORD_DEFAULTを指定し、パスワードをハッシュ化する。
        password_hash()関数は自動的に安全なソルトを生成してくれる。
        (ハッシュ値を取得するたびにソルトが自動生成されるので、同じパスワードでもハッシュ値が変わる)
        */
        // $hash = password_hash($password, PASSWORD_DEFAULT, $options);    //何故か使えない？
        $hash = password_hash($password, PASSWORD_DEFAULT);

        return $hash;
    }

    /**
     * メールアドレス入力チェック
     *
     * @param string $email
     * @return boolean
     */
    public function judgeMailAdr($email)
    {
        if (preg_match('/^[\w\-\.]+\@[\w\-\.]+\.([a-z]+)$/', $email) == 0) {
            return false;
        }
        return true;
    }

    /**
     * XSS対策のためのHTMLエスケープ
     *
     * @param array|string $data
     * @param string $charset
     * @return array|string 変換後文字列
     */
    public function xssEs($data, $charset = 'UTF-8')
    {
        // $dataが配列のとき
        if (is_array($data)) {
            // 再帰呼び出し
            return array_map(__METHOD__, $data);
        } else {
            // HTMLエスケープを行う
            if (!is_null($data)){
                return htmlspecialchars($data, ENT_QUOTES, $charset);
            }
        }
    }

    /**
     * ラインフィード追加
     *
     * @param array|string $data
     * @return string
     */
    public function addLinefeed($data)
    {
        // $dataが配列のとき
        if (is_array($data)) {
            $str = "";
            foreach ($data as $value) {
                $str .= ($value . PHP_EOL);
            }
            return $str;
        } else {
            // LF追加
            $data .= PHP_EOL;
            return $data;
        }
    }

    /**
     * 改行を消す(メールヘッダ・インジェクション対策)
     *
     * @param string|array $str
     * @return string|array
     */
    public function rm($str)
    {
        if (isset($str)) {
            $str = str_replace(array("\r\n", "\r", "\n"), '', $str);
        }
        return $str;
    }

    /**
     * スペース削除(半角・全角)
     *
     * @param string $str
     * @return string
     */
    public function rs($str)
    {
        if (isset($str)) {
            $str = str_replace(array(" ", "　"), '', $str);
        }
        return $str;
    }

    /**
     * スペース削除(半角・全角・タブ)
     *
     * @param string $str
     * @return string
     */
    public function removeSpace($str)
    {
        if (isset($str)) {
            $str = str_replace(array(" ", "　", "\t"), '', $str);
        }
        return $str;
    }

    /**
     * スペース削除(半角・全角・タブ・改行)
     *
     * @param string $str
     * @return string
     */
    public function removeSpecial($str)
    {
        if (isset($str)) {
            $str = str_replace(array(" ", "　", "\t", "\r\n", "\r", "\n"), '', $str);
        }
        return $str;
    }

    /**
     * 英数小文字記号のパスワードを生成する
     *
     * @param integer $length   桁数(default=8)
     * @return string
     */
    public function createPasswd($length = 8)
    {
        //vars
        $pwd = array();
        $pwd_strings = array(
            "sletter" => range('a', 'z'),
            "cletter" => range('A', 'Z'),
            "number" => range('0', '9'),
            // "symbol" => array_merge(array('!', '#', '%', '$')),
        );

        //logic
        while (count($pwd) < $length) {
            // 4種類必ず入れる
            if (count($pwd) < 3) {
            // if (count($pwd) < 4) {
                $key = key($pwd_strings);
                next($pwd_strings);
            } else {
                // 後はランダムに取得
                $key = array_rand($pwd_strings);
            }
            $pwd[] = $pwd_strings[$key][array_rand($pwd_strings[$key])];
        }
        // 生成したパスワードの順番をランダムに並び替え
        shuffle($pwd);

        return implode($pwd);
    }

    /**
     * 入力半角カナを全角変換
     *
     * @param string $str_kana
     * @return string
     */
    public function modifyKana($str_kana)
    {
        $ret = mb_convert_kana($str_kana, 'KV');
        return $ret;
    }

    /**
     * "selecetd返信処理"
     *
     * @param string $val
     * @param string $inp
     * @return string
     */
    public function selectVal($val, $inp)
    {
        if ($val == $inp) {
            return " selected";
        }
        return "";
    }

    /**
     * "checked返信処理"
     *
     * @param string $val
     * @param string $inp
     * @return string
     */
    public function chkVal($val, $inp)
    {
        if ($val == $inp) {
            return " checked";
        }
        return "";
    }

    /**
     * うるう年判定
     *
     * @param integer $year
     * @return boolean
     */
    public function judgeLeapYear($year)
    {
        // if ($year % 4 == 0 && $year % 100 == 0 && $year % 400 != 0) {
        //     return true;
        // } else {
        //     return false;
        // }
        $ndate = new DateTime($year . '-01-01');
        $uruu = $ndate->format('L');

        if ($uruu == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 日本の休日取得
     * JSON形式が取得できない場合に、iCal形式から取得する
     * 期間の指定などは不可
     * 前後3年分ほどが取得できる
     * @return array
     */
    public function japanHolidayIcs()
    {
        // カレンダーID
        $calendar_id = urlencode('japanese__ja@holiday.calendar.google.com');

        $url = 'https://calendar.google.com/calendar/ical/' . $calendar_id . '/public/full.ics';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        if (!empty($result)) {
            $items = $sort = array();
            $start = false;
            $count = 0;
            foreach (explode("\n", $result) as $row => $line) {
                // 1行目が「BEGIN:VCALENDAR」でなければ終了
                if (0 === $row && false === stristr($line, 'BEGIN:VCALENDAR')) {
                    break;
                }

                // 改行などを削除
                $line = trim($line);

                // 「BEGIN:VEVENT」なら日付データの開始
                if (false !== stristr($line, 'BEGIN:VEVENT')) {
                    $start = true;
                } elseif ($start) {
                    // 「END:VEVENT」なら日付データの終了
                    if (false !== stristr($line, 'END:VEVENT')) {
                        $start = false;

                        // 次のデータ用にカウントを追加
                        ++$count;
                    } else {
                        // 配列がなければ作成
                        if (empty($items[$count])) {
                            $items[$count] = array('date' => null, 'title' => null);
                        }

                        // 「DTSTART;～」（対象日）の処理
                        if (0 === strpos($line, 'DTSTART;VALUE')) {
                            $date = explode(':', $line);
                            $date = end($date);
                            $y = mb_substr($date, 0, 4);
                            $m = mb_substr($date, 4, 2);
                            $d = mb_substr($date, 6, 4);
                            $items[$count]['date'] = $date;
                            // ソート用の配列にセット
                            // $sort[$count] = $date;
                            $sort[$count] = sprintf('%s-%s-%0s', $y, $m, $d);
                        } elseif (0 === strpos($line, 'SUMMARY:')) { // 「SUMMARY:～」（名称）の処理
                            list($title) = explode('/', substr($line, 8));
                            $items[$count]['title'] = trim($title);
                        }
                    }
                }
            }

            // 日付でソート
            $items = array_combine($sort, $items);
            ksort($items);

            return $items;
        }
    }

    /**
     * 文字数切り取り(改行、タグ削除)
     *
     * @param string $str   切り取り元文字列
     * @param integer $len  切り取り文字数
     * @param string $addstr    切り取り最後に付加文字列
     * @return string
     */
    public function strLimitlength($str, $len, $addstr)
    {
        $ret_str = "";
        $alen = mb_strlen($addstr);
        $tem_str = str_replace(array("\r\n", "\r", "\n"), '', strip_tags($str));
        if (mb_strlen($tem_str) > ($len - $alen)) {
            $ret_str = mb_substr($tem_str, 0, $len - $alen) . $addstr;
        } else {
            $ret_str = $tem_str;
        }
        return $ret_str;
    }

    /**
     * 日付差分計算
     *
     * @param string $day1_str  YYYY-MM-DD
     * @param string $day2_str  YYYY-MM-DD
     * @return integer  差日数
     */
    public function subDate($day1_str, $day2_str)
    {
        $day1 = new DateTime($day1_str);
        $day2 = new DateTime($day2_str);
        $interval = $day1->diff($day2);
        return intval($interval->format('%a'));
    }

    /**
     * "selecetd返信処理"
     *
     * @param string $val
     * @param array $inp
     * @return string
     */
    public function selectValArray($val, $inp)
    {
        if (empty($inp)) {
            return "";
        }
        foreach ($inp as $value) {
            if ($val == $value) {
                return " checked";
            }
        }
        return "";
    }

    /**
     * 配列をサニタイズ
     *
     * @param string $string
     * @return void
     */
    public function arraySanitize($string)
    {
        if (is_array($string)) {
            return array_map("arraySanitize", $string);
        } else {
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        }
    }

    /**
     * 文字列を指定の長さに間引いて"..."をつける
     *
     * @param string $src
     * @param integer $len
     * @param string $addstr
     * @return void
     */
    public function strWidth($src, $len, $addstr = "...")
    {
        $ret = mb_strimwidth(str_replace(array("\r\n", "\r", "\n"), '', strip_tags($src)), 0, $len, $addstr, "UTF-8");
        return $ret;
    }

    /**
     * 暗号化
     *
     * @param string $str   : 暗号前文字列
     * @param string $key   : 暗号キー
     * @param string $ivhex : 初期化ベクター(複合時に必要)
     * @param string $methodname
     * @return string
     */
    public function sencript($str, $key, &$ivhex, $methodname = "AES-256-CBC")
    {
        $ivhex = bin2hex(openssl_random_pseudo_bytes(openssl_cipher_iv_length($methodname))); // 複合に必要なのでこれも保存
        $iv = hex2bin($ivhex);
        $base64_encrypt_string = openssl_encrypt($str, $methodname, $key, 0, $iv);
        return $base64_encrypt_string;
    }

    /**
     * 複合化
     *
     * @param string $str   : 複合文字列
     * @param string $key   : 暗号キー
     * @param string $ivhex : 初期化ベクター(暗号時に取得したもの)
     * @param string $methodname
     * @return string
     */
    public function sdecrypt($str, $key, $ivhex, $methodname = "AES-256-CBC")
    {
        $iv = hex2bin($ivhex);
        $decrypt_string = openssl_decrypt($str, $methodname, $key, 0, $iv);
        return $decrypt_string;
    }

    /**
     * $_POST取得関数
     *
     * @param [array] $a_tagname nameの配列
     * @return array
     */
    public function getPost($a_tagname): array
    {
    $array = [];
    foreach($a_tagname as $value){
        $item = filter_input(INPUT_POST, "{$value}");
        if ($item === false){
        $array["{$value}"] = filter_input(INPUT_POST, "{$value}", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        } else {
        $array["{$value}"] = $item;
        }
    }
    return $array;
    }

    /**
     * $_GET取得関数
     *
     * @param [array] $a_tagname nameの配列
     * @return array
     */
    public function getGET($a_tagname): array
    {
    $array = [];
    foreach($a_tagname as $value){
        $item = filter_input(INPUT_GET, "{$value}");
        if ($item === false){
        $array["{$value}"] = filter_input(INPUT_GET, "{$value}", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        } else {
        $array["{$value}"] = $item;
        }
    }
    return $array;
    }

    function parsePath($path) {
        $pathUrl = parse_url($path);
        $pathInfo = (isset($pathUrl['path']))? pathinfo($pathUrl['path']): array();
        
        $fileName = ''; // ファイル名
        if ( isset($pathInfo['filename']) && isset($pathInfo['extension']) ) {
           $fileName = $pathInfo['filename'] . '.' . $pathInfo['extension'];
        }
        
        //$withQuery = ''; // getパラメーターつきファイル名・・・保留中
        //if ( isset($pathUrl['query']) ) {
        //   $withQuery = $fileName . '?' . $pathUrl['query'];
        //}
        
        $pos = false;
        if ( $fileName != '' ) {
           $pos = strrpos( $path, $fileName );
        }
        
        if ( $pos===false ) {
           $resultUrl  = $path;
           $resultFile = '';
        }
        else {
           $resultUrl  = substr($path, 0, $pos);
           $resultFile = substr($path, $pos);
        }
        
        // return array(
        //    $resultUrl,  // ファイル名の前
        //    $resultFile, // ファイル名（パラメータ付き）
        //    $fileName,   // ファイル名
        // );
        return $resultUrl;
    }
    
    function jsMinify($js) {
        // 置換用の配列を生成
        $js_replaces = [];

        // (1) JSの正規表現前後の空白文字列の除去
        $js_replaces[ '/([(+=])\s*(\/(?:(?!(?<!\\\)\/).)+\/[dgimsuy]*)\s*([)+,.;])/s' ] = '${1}${2}${3}';

        // (2) コメントの除去
        $js_replaces[ '/(\/\*[!@].*?\*\/|[(+=]\/(?:(?!(?<!\\\)\/).)+\/[dgimsuy]*[)+,.;]|\"(?:(?!(?<!\\\)\").)*\"|\'(?:(?!(?<!\\\)\').)*\'|\`(?:(?!(?<!\\\)\`).)*\`)|\/\*.*?\*\/|\/\/[^\r\n]+[\r\n]/s' ] = '${1}';

        // (3) 1つ以上連続する空白文字列の置換
        $js_replaces[ '/(\/\*[!@].*?\*\/|[(+=]\/(?:(?!(?<!\\\)\/).)+\/[dgimsuy]*[)+,.;]|\"(?:(?!(?<!\\\)\").)*\"|\'(?:(?!(?<!\\\)\').)*\'|\`(?:(?!(?<!\\\)\`).)*\`)\s*|\s+/s' ] = '${1} ';

        // (4) 記号前後の半角スペースの除去
        $js_replaces[ '/(\/\*[!@].*?\*\/|[(+=]\/(?:(?!(?<!\\\)\/).)+\/[dgimsuy]*[)+,.;]|\"(?:(?!(?<!\\\)\").)*\"|\'(?:(?!(?<!\\\)\').)*\'|\`(?:(?!(?<!\\\)\`).)*\`) | ([!#$%&)*+,\-.\/:;<=>?@\]^_|}~]) | ([!#$%&)*,.\/:;<=>?@\]^_|}~]|\+(?!\+)|-(?!-)|\z)|([!#$%&()*+,\-.\/:;<=>?@\[\]^_{|}~]|\A) /s' ] = '${1}${2}${3}${4}';

        // 一括置換
        $js = preg_replace( array_keys( $js_replaces ), array_values( $js_replaces ), $js);

        return $js;
    }

    /**
     * 連想配列からセレクトボックスを生成します。
     * @param $inputName name属性
     * @param $srcArray 元となる連想配列
     * @param $selectedIndex selected属性を付加するインデックス
     * @param bool $value tureならプルダウンの項目とvalueを同じにする　falseなら連想配列のキーをvalueにする
     * @param bool $optgroup tureならグループ化する
     * @return String
     */
    public function create_pull($srcArray = [], $selectedIndex = "", $inputName = null, $value = true, $optgroup = false) {
        $temphtml = "";
        if(!is_null($inputName)){
            $temphtml .= '<select name="'. htmlspecialchars($inputName). '">'. "\n";
        }
    
        if ($optgroup){
        foreach ($srcArray as $optkey => $optval){
            $temphtml .= '<optgroup label="'. htmlspecialchars($optkey). '">';
            foreach ($optval as $key => $val) {
                if ($value){
                    if ($selectedIndex == $val) {
                        $selectedText = ' selected="selected"';
                    } else {
                        $selectedText = '';
                    }
                    $temphtml .= '<option value="'. htmlspecialchars($val). '"'. $selectedText. '>'. htmlspecialchars($val). '</option>'. "\n";
                } else {
                    if ($selectedIndex == $key) {
                        $selectedText = ' selected="selected"';
                    } else {
                        $selectedText = '';
                    }
                    $temphtml .= '<option value="'. htmlspecialchars($key). '"'. $selectedText. '>'. htmlspecialchars($val). '</option>'. "\n";
                }
            }
            $temphtml .= '</optgroup>';
        }
        } else {
            foreach ($srcArray as $key => $val) {
                if ($value){
                    if ($selectedIndex == $val) {
                        $selectedText = ' selected="selected"';
                    } else {
                        $selectedText = '';
                    }
                    $temphtml .= '<option value="'. htmlspecialchars($val). '"'. $selectedText. '>'. htmlspecialchars($val). '</option>'. "\n";
                } else {
                    if ($selectedIndex == $key) {
                        $selectedText = ' selected="selected"';
                    } else {
                        $selectedText = '';
                    }
                    $temphtml .= '<option value="'. htmlspecialchars($key). '"'. $selectedText. '>'. htmlspecialchars($val). '</option>'. "\n";
                }
            }
        }
    
        if(!is_null($inputName)){
            $temphtml .= '</select>'. "\n";
        }
        return $temphtml;
    }

    /**
     * ダウンロード関数
     *
     * @param [str] $dir ディレクトリ
     * @param [str] $file_name ファイル名（パス）
     * @param [str] $rename ファイル名（ダウンロード時）
     * @param [str] $mime_type ex. application/octet-stream, application/pdf
     * @return bool
     */
    public function download($dir, $file_name, $rename, $mime_type) {
        // ex. dir: /tmp/download/
        // ex. file_name: test.pdf
        $fullpath = $dir . $file_name;
        
        // ファイルを読み込みできない場合はエラー
        if (!is_readable($fullpath)) {
            return false;
        }
        
        // mimeタイプの指定が無い場合はoctet-streamを設定
        if (empty($mime_type)) {
            $mime_type = "application/octet-stream";
        }
        
        // ダウンロードファイル名の設定
        if (empty($rename)) {
            $rename = $file_name;
        }
        
        // Content-Typeにmimeタイプを設定
        header('Content-Type: ' . $mime_type);
        
        // mimeタイプの自動判定の抑止
        header('X-Content-Type-Options: nosniff');
        
        // ダウンロードファイルのサイズ
        header('Content-Length: ' . filesize($fullpath));
        
        // ダウンロード時のファイル名
        header('Content-Disposition: attachment; filename="' . $rename . '"');
        
        // 出力バッファリングをすべて無効化する
        while (ob_get_level()) { ob_end_clean(); }
        
        // 出力
        readfile($fullpath);

        return true;
        
        exit;
        }

    /**
     * GETパラメータ取得
     *
     * @param array $filters : フィルター
     * @param array $options : オプション
     * @return array
     */
    function filterGet(array $filters, array $options): array
    {
    $getval = array();
    $array_keys = array_keys($_GET);

    foreach ($array_keys as $key => $value) {
        $fileter = isset($filters[$key]) ? $filters[$key] : FILTER_DEFAULT;
        $option = isset($options[$key]) ? $options[$key] : 0;
        $getval[$value] = filter_input(INPUT_GET, $value, $fileter, $option);
    }
    return $getval;
    }
}

