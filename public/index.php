<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// TODO: エラー関連
ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);
error_reporting(E_ALL);
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}, E_ALL ^ E_DEPRECATED ^ E_USER_DEPRECATED ^ E_USER_NOTICE);

require_once __DIR__ . '/../src/dependencies.php';

// TODO: 入力のValidation
$mode = filter_input(INPUT_GET, 'mode');
$title = filter_input(INPUT_POST, 'title');
$body = filter_input(INPUT_POST, 'body');
$token = filter_input(INPUT_POST, 'token');

require_once __DIR__ . '/../src/route.php';

// TODO: 投稿の作成
// TODO: 投稿の一覧
// TODO: 投稿の詳細
// TODO: 管理機能（記事の削除・記事の編集）