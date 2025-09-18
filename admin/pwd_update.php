<?php

/**
 * @file pwd_update.php
 * @brief パスワード変更処理
 * @date 2023-06-08
 *
 * Copyright isis Co.,ltd.
 */

require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");
chk_session(DF_SES_NAM);

$logid = $_SESSION['logid'];

$old = filter_input(INPUT_POST, 'old');
$new = filter_input(INPUT_POST, 'new');
$conf = filter_input(INPUT_POST, 'conf');

if (empty($old) || empty($new) || empty($conf)){
  header("Location: pwd.php?MS=100");
  exit;
}

$pattern = "/^([a-zA-Z0-9-_]{6,50})$/";
$result = preg_match($pattern, $new);

if ($result !== 1){
  header("Location: pwd.php?MS=100");
  exit;
}

if ($new !== $conf){
  header("Location: pwd.php?MS=100");
  exit;
}

try {
  $sql = "SELECT am_pass FROM admin_tb WHERE am_id = BINARY ?";
  $bind = [];
  $bind[] = $logid;
  $am_pass = $db->setSql($sql, $bind, PDO::FETCH_COLUMN, "fetch");

  if (!password_verify($old, (string)$am_pass)){
    header("Location: pwd.php?MS=100");
    exit;
  }

  $sql = "UPDATE admin_tb SET am_pass = ? WHERE am_id = BINARY ?";
  $bind = [];
  $bind[] = password_hash($new, PASSWORD_DEFAULT);
  $bind[] = $logid;
  $db->setSql($sql, $bind);

  header("Location: logout.php?MS=10");
  exit;

} catch (Exception $e){
  header("Location: pwd.php?MS=100");
  exit;
}