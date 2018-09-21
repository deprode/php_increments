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
$container->set(\App\Action\TopAction::class, function ($c) {
    return new \App\Action\TopAction($c->get('ArticleRepository'), $c->get('TopResponder'), $c->get('Security'), $c->get('settings'));
});

/**
 * Model
 */
$container->set('ArticleRepository', function () {
    return new \App\Domain\ArticleRepository();
});

/**
 * Responder
 */
$container->set('TopResponder', function ($c) {
    return new \App\Responder\TopResponder($c->get('View'));
});
