<?php

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
        'cache'    => '../cache',
        'template' => '../templates',
    ];
});

$container->set('Twig', function ($c) {
    $setting = $c->get('settings');
    // テンプレートをTwigにする
    $loader = new Twig_Loader_Filesystem($setting['template']);
    $twig = new Twig_Environment($loader, [
        'cache' => $setting['cache'],
    ]);

    return $twig;
});

$container->set('View', function ($c) {
    return new App\Twig($c->get('Twig'));
});

$container->set('Security', function () {
    return new \App\Security();
});

/**
 * Action
 */
$container->set(\App\Action\TopAction::class, function ($c) {
    return new \App\Action\TopAction($c->get(\App\Domain\Top::class), $c->get('TopResponder'));
});

$container->set(\App\Action\ArticleSaveAction::class, function ($c) {
    return new \App\Action\ArticleSaveAction($c->get(\App\Domain\ArticleSave::class));
});

/**
 * Model
 */
$container->set(\App\Domain\Top::class, function ($c) {
    return new \App\Domain\Top($c->get('ArticleRepository'), $c->get('Security'), $c->get('settings'));
});
$container->set(\App\Domain\ArticleSave::class, function ($c) {
    return new \App\Domain\ArticleSave($c->get('ArticleRepository'), $c->get('Security'));
});

/**
 * Repository
 */
$container->set('ArticleRepository', function () {
    return new \App\Repository\ArticleRepository();
});

/**
 * Responder
 */
$container->set('TopResponder', function ($c) {
    return new \App\Responder\TopResponder($c->get('View'));
});
