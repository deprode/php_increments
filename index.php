<?php

// TODO: クラスをautoloadで読み込む
require_once 'vendor/autoload.php';

session_start();

// TODO: エラー関連
ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);
error_reporting(E_ALL);
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}, E_ALL ^ E_DEPRECATED ^ E_USER_DEPRECATED ^ E_USER_NOTICE);

// TODO: 入力のValidation
$mode = filter_input(INPUT_GET, 'mode');
$title = filter_input(INPUT_POST, 'title');
$body = filter_input(INPUT_POST, 'body');
$token = filter_input(INPUT_POST, 'token');

// TODO: blogの設定を設定DBやdotenvから読み込む
$settings = [
    'title'    => 'PHP Increment',
    'subtitle' => 'Hello World!',
    'author'   => 'deprode.net',
    'cache'    => 'cache'
];

$blog_title = $settings['title'];
$blog_subtitle = $settings['subtitle'];
$author = $settings['author'];
$cache = $settings['cache'];

require "src/route.php";

// TODO: DBALでブログ記事取得
$posts = [
    '0' => [
        'title' => 'ブログタイトル',
        'body'  => 'ブログの内容',
        'date'  => '2018-10-10 10:10:10'
    ],
    '1' => [
        'title' => 'ブログタイトル2',
        'body'  => 'ブログの内容2',
        'date'  => '2018-11-11 11:11:11'
    ],
];

// TODO: クラスにDI


// TODO: フォームにCSRF対策
$security = new \App\Security();
$token = $security->generateToken($token);
$security->checkToken($token, $_SESSION['token']);

// TODO: 管理機能（記事の削除・記事の編集）


// TODO: テンプレートをTwigにする
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
    'cache' => $cache,
));

$security->outputSecureHeader();

echo $twig->render('index.twig', [
    'blog_title'    => $blog_title,
    'blog_subtitle' => $blog_subtitle,
    'posts'         => $posts,
    'token'         => $token,
    'author'        => $author,
]);
