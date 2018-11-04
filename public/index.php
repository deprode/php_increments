<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// エラー関連の設定は開発時かつcliの時だけ
if (PHP_SAPI === 'cli-server') {
    ini_set("display_errors", 0);
    ini_set("display_startup_errors", 0);
    error_reporting(E_ALL);

    set_error_handler(function ($errno, $errstr, $errfile, $errline) {
        throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
    }, E_ALL ^ E_DEPRECATED ^ E_USER_DEPRECATED ^ E_USER_NOTICE);
}

set_exception_handler(function ($e) {
    $fp = fopen('php://stderr', 'w');
    fputs($fp, $e);
    header('Content-Type: text/plain; charset=utf-8', true, 500);
    echo 'エラーです';
    exit(1);
});

require_once __DIR__ . '/../src/dependencies.php';

require_once __DIR__ . '/../src/route.php';

// TODO: 投稿の一覧
// TODO: 投稿の詳細
// TODO: 管理機能（記事の削除・記事の編集）