<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Middlewares\Utils\Dispatcher;

// FastRouteでルーティング
$simpleDispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->get('/', \App\Action\TopAction::class);
});

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

$dispatcher = new Dispatcher([
    new Middlewares\FastRoute($simpleDispatcher),
    new Middlewares\RequestHandler($container)
]);

$response = $dispatcher->dispatch($request);
//TODO: 次のコミットでエラー対策する

$emitter = new Zend\HttpHandlerRunner\Emitter\SapiEmitter();
$emitter->emit($response);