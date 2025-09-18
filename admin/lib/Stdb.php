<?php
// DB操作クラス
namespace Stdb\Stdb;

use \PDO;

class Stdb
{
    private $line;
    private $pdo;
    private $dbname;
    private $host;
    private $user;
    private $pass;
    private $port;
    const DB_NAME = '';
    const HOST = '';
    const USER = '';
    const PASS = '';
    const PORT = '';

    /**
     * DB接続初期設定
     *
     * @param string $dbname
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $port
     * @return void
     */
    public function setConnect(
        string $dbname = null,
        string $host = null,
        string $user = null,
        string $pass = null,
        string $port = null
    ) {
        $this->dbname = (is_null($dbname) || $dbname == '') ? self::DB_NAME : $dbname;
        $this->host = (is_null($host) || $host == '') ? self::HOST : $host;
        $this->user = (is_null($user) || $user == '') ? self::USER : $user;
        $this->pass = (is_null($pass) || $pass == '') ? self::PASS : $pass;
        $this->port = $port;
        if (is_null($port)) {
            $dbdns = "mysql:dbname=" . $this->dbname . ";host=" . $this->host . ";charset=utf8mb4";
        } else {
            $dbdns = "mysql:dbname=" . $this->dbname .
                ";host=" . $this->host . ";port=" . $this->port . ";charset=utf8mb4";
        }
        $pdo = new \PDO($dbdns, $this->user, $this->pass);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true); //←この行追加でrowCountOK
        $this->pdo = $pdo;
        $this->line = 0;
    }

    /**
     * トランザクション処理開始
     *
     * @return void
     */
    public function setTransaction()
    {
        $this->pdo->beginTransaction();
    }

    /**
     * トランザクション(コミット)処理
     *
     * @return void
     */
    public function comit()
    {
        $this->pdo->commit();
    }

    // トランザクション(ロールバック)処理
    public function rollback()
    {
        $this->pdo->rollBack();
    }

    /**
     * SQL文セット
     *
     * @param string $sql
     * @param mixed $bind
     * @param integer $fetch_arg
     * @param string $method
     * @return void
     */
    public function setSql(string $sql, $bind = null, int $fetch_arg = PDO::FETCH_ASSOC, string $method = "fetchall")
    {
        if (!is_array($bind) && isset($bind)) {
            $temp = $bind;
            $bind = array();
            $bind[] = $temp;
        }
        $stm = $this->pdo->prepare($sql);
        $cnt = 1;
        if ($bind != null) {
            foreach ($bind as $value) {
                if (is_float($value)) {   //単価だけfloatで固定
                    $stm->bindValue($cnt, $value, PDO::PARAM_STR);
                } elseif (is_null($value)) {
                    $stm->bindValue($cnt, null, PDO::PARAM_NULL);  //nullは入らず（実験中）
                } else {
                    $stm->bindValue($cnt, $value);
                }
                $cnt++;
            }
        }
        $stm->execute();
        // if (mb_strpos($sql, "INSERT") !== false) {
        //     $this->line = $this->pdo->lastInsertId();
        // }
        $this->line = $stm->rowCount();
        if (mb_strpos($sql, "INSERT") !== false) {
            $this->line = $this->pdo->lastInsertId();
        }

        if (mb_strpos($sql, "SELECT") !== false) {
            return $stm->$method($fetch_arg);
        } else {
            return null;
        }
        // return $stm->fetchall($fetch_arg);
    }

    /**
     * 更新ライン番号取得
     *
     * @return integer
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * SQLバージョン取得
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    }

    /**
     * テーブル有無確認
     *
     * @param string $tableName
     * @return boolean
     */
    public function existTable(string $tableName): bool
    {
        $sql = "SHOW TABLES LIKE '{$tableName}'";
        $stm = $this->pdo->query($sql);
        $ret = $stm->fetchall(PDO::FETCH_COLUMN);
        if (count($ret)) {
        return true;
        } else {
        return false;
        }
    }
}
