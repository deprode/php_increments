<?php

session_start();

// TODO: セキュリティヘッダ
header('X-Frame-Options: SAMEORIGIN');
header("X-XSS-Protection: 1; mode=block");
header('X-Content-Type-Options: nosniff');
header('Content-Type: text/html; charset=UTF-8');

// TODO: エラー関連
ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);
error_reporting(E_ALL);
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}, E_ALL ^ E_DEPRECATED ^ E_USER_DEPRECATED ^ E_USER_NOTICE);


// TODO: クラスをautoloadで読み込む
require_once 'vendor/autoload.php';

// TODO: 入力のValidation
$mode = filter_input(INPUT_GET, 'mode');
$title = filter_input(INPUT_POST, 'title');
$body = filter_input(INPUT_POST, 'body');
$token = filter_input(INPUT_POST, 'token');
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// TODO: blogの設定を設定DBやdotenvから読み込む
$settings = [
    'title'    => 'PHP Increment',
    'subtitle' => 'Hello World!',
    'author'   => 'deprode.net'
];

$blog_title = $settings['title'];
$blog_subtitle = $settings['subtitle'];
$author = $settings['author'];

// TODO: ルーティング作ってFirstRoute
switch ($mode) {
    case 'admin':
        $blog_title = '管理モード ' . $blog_title;
        break;
    case 'post':
        $blog_title = '投稿モード ' . $blog_title;
        break;
    default:
        break;
}

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
if (empty($token)) {
    try {
        $token = bin2hex(random_bytes(24));
    } catch (Exception $e) {
        exit(1);
    }
    $_SESSION['token'] = $token;
} else {
    $token = $_SESSION['token'];
}
if (empty($_SESSION['token']) || $token !== $_SESSION['token']) {
    die('正規の画面から投稿してください');
}

// TODO: 管理機能（記事の削除・記事の編集）


// TODO: テンプレートをTwigにする
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $blog_title; ?></title>
</head>
<body>
<header>
    <h1 class="title"><?= $blog_title; ?></h1>
    <p class="subtitle"><?= $blog_subtitle; ?></p>
</header>
<nav>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li><?= h($post['title']); ?></li>
        <?php endforeach; ?>
    </ul>
</nav>
<main>
    <?php foreach ($posts as $post): ?>
        <article>
            <h2><?= h($post['title']); ?></h2>
            <p><?= h($post['body']); ?></p>
        </article>
    <?php endforeach; ?>
    <h3>新しい記事を投稿する</h3>
    <form action="./">
        <input type="hidden" name="mode" value="post">
        <input type="hidden" name="token" value="<?= h($token) ?>">
        <label for="title">タイトル：<input type="text" name="title" value=""></label><br>
        <textarea name="body" cols="30" rows="10" placeholder="ブログの内容"></textarea>
    </form>
</main>
<footer>
    ©<?= $author ?>
</footer>
</body>
</html>
