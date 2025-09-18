<?php

/************************************
 ** logout.php
 ** ログアウト処理
 ***********************************/
require_once(__DIR__ . "/common/common_setting.php");
require_once(__DIR__ . "/lib/md_session.php");

mysession_destroy();

$err_code = filter_input(INPUT_GET, 'ER', FILTER_SANITIZE_SPECIAL_CHARS);
switch ($err_code) {
  case 10:
    header("Location: login.php?ER=10");
    break;
  case 200:
    header("Location: login.php?ER=200");
    break;
  default:
    header("Location: login.php?ER=999");
    break;
}
