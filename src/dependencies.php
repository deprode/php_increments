<?php

use Psr\Container\ContainerInterface;

// DI
$container = new DI\Container();

/**
 * 便利クラス // TODO: ここのコメントを考える
 */
$container->set('settings', function () {
    // TODO: blogの設定を設定DBから読み込む
    return [
        'title'       => 'PHP Increment',
        'subtitle'    => 'Hello World!',
        'author'      => 'deprode.net',
        'cache'       => getenv('CACHE_PATH'),
        'template'    => getenv('TEMPLATE_PATH'),
        'db_name'     => getenv('DB_NAME'),
        'db_user'     => getenv('DB_USER'),
        'db_password' => getenv('DB_PASSWORD'),
        'db_host'     => getenv('DB_HOST'),
        'db_driver'   => getenv('DB_DRIVER'),
    ];
});

$container->set('Twig', function (ContainerInterface $c) {
    $setting = $c->get('settings');
    // テンプレートをTwigにする
    $loader = new Twig_Loader_Filesystem($setting['template']);
    $twig = new Twig_Environment($loader, [
        'cache' => $setting['cache'],
    ]);

    return $twig;
});

$container->set('View', function (ContainerInterface $c) {
    return new App\Twig($c->get('Twig'));
});

$container->set('Security', function () {
    return new \App\Security();
});

$container->set('DBAL', function (ContainerInterface $c) {
    $setting = $c->get('settings');
    $config = new \Doctrine\DBAL\Configuration();

    $connectionParams = [
        'dbname'   => $setting['db_name'],
        'user'     => $setting['db_user'],
        'password' => $setting['db_password'],
        'host'     => $setting['db_host'],
        'driver'   => $setting['db_driver'],
    ];

    return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
});

$container->set('Database', function (ContainerInterface $c) {
    return new App\Database($c->get('DBAL'));
});

$container->set('Carbon', function () {
    return new Carbon\Carbon();
});

/**
 * Action
 */
$container->set(\App\Action\TopAction::class, function (ContainerInterface $c) {
    return new \App\Action\TopAction($c->get(\App\Domain\Top::class), $c->get('TopResponder'));
});
$container->set(\App\Action\ArticleSaveAction::class, function (ContainerInterface $c) {
    return new \App\Action\ArticleSaveAction($c->get(\App\Domain\ArticleSave::class));
});
$container->set(\App\Action\ArticleAction::class, function (ContainerInterface $c) {
    return new \App\Action\ArticleAction($c->get(\App\Domain\Article::class), $c->get('ArticleResponder'));
});

/**
 * Model
 */
$container->set(\App\Domain\Top::class, function (ContainerInterface $c) {
    return new \App\Domain\Top($c->get('ArticleRepository'), $c->get('Security'), $c->get('settings'));
});
$container->set(\App\Domain\ArticleSave::class, function (ContainerInterface $c) {
    return new \App\Domain\ArticleSave($c->get('ArticleRepository'), $c->get('Security'), $c->get('Carbon'));
});
$container->set(\App\Domain\Article::class, function (ContainerInterface $c) {
    return new \App\Domain\Article($c->get('ArticleRepository'), $c->get('settings'));
});

/**
 * Repository
 */
$container->set('ArticleRepository', function (ContainerInterface $c) {
    return new \App\Repository\ArticleRepository($c->get('Database'));
});

/**
 * Responder
 */
$container->set('TopResponder', function (ContainerInterface $c) {
    return new \App\Responder\TopResponder($c->get('View'));
});
$container->set('ArticleResponder', function (ContainerInterface $c) {
    return new \App\Responder\ArticleResponder($c->get('View'));
});
