<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Middlewares\Utils\Dispatcher;

// FastRouteでルーティング
$simpleDispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->get('/', \App\Action\TopAction::class);
    $r->post('/', \App\Action\ArticleSaveAction::class);
    $r->get('/article/{id}', \App\Action\ArticleAction::class);
});

$dispatcher = new Dispatcher([
    new App\Middleware\SecurityHeaderMiddleware(),
    new Middlewares\ErrorHandler(new App\Handler\ErrorRequestHandler()),
    new Middlewares\FastRoute($simpleDispatcher),
    new Middlewares\RequestHandler($container),
]);

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();
$response = $dispatcher->dispatch($request);

$emitter = new Zend\HttpHandlerRunner\Emitter\SapiEmitter();
$emitter->emit($response);