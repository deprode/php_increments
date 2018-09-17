<?php

require_once '../vendor/autoload.php';

session_start();

// TODO: エラー関連
ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);
error_reporting(E_ALL);
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}, E_ALL ^ E_DEPRECATED ^ E_USER_DEPRECATED ^ E_USER_NOTICE);

// DI
$container = new DI\Container();

/**
 * 便利クラス // TODO: ここのコメントを考える
 */
$container->set('settings', function () {
    // TODO: blogの設定を設定DBやdotenvから読み込む
    return [
        'title'    => 'PHP Increment',
        'subtitle' => 'Hello World!',
        'author'   => 'deprode.net',
        'cache'    => '../cache'
    ];
});

$container->set('View', function ($c) {
    $setting = $c->get('settings');

    // テンプレートをTwigにする
    $loader = new Twig_Loader_Filesystem('../templates');
    $twig = new Twig_Environment($loader, array(
        'cache' => $setting['cache'],
    ));

    return $twig;
});

$container->set('Security', function () {
    return new \App\Security();
});

/**
 * Action
 */
$container->set('\App\Action\TopAction', function ($c) {
    return new \App\Action\TopAction($c->get('ArticleRepository'), $c->get('View'), $c->get('Security'), $c->get('settings'));
});

/**
 * Model
 */
$container->set('ArticleRepository', function () {
    return new \App\Domain\ArticleRepository();
});


// TODO: 入力のValidation
$mode = filter_input(INPUT_GET, 'mode');
$title = filter_input(INPUT_POST, 'title');
$body = filter_input(INPUT_POST, 'body');
$token = filter_input(INPUT_POST, 'token');


require "../src/Route.php";

// TODO: 投稿の作成
// TODO: 投稿の一覧
// TODO: 投稿の詳細
// TODO: 管理機能（記事の削除・記事の編集）