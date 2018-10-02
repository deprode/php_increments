<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Middlewares\Utils\Dispatcher;

// FastRouteでルーティング
$simpleDispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->get('/', \App\Action\TopAction::class);
});

$dispatcher = new Dispatcher([
    new Middlewares\ErrorHandler(new App\Handler\ErrorRequestHandler()),
    new Middlewares\FastRoute($simpleDispatcher),
    new Middlewares\RequestHandler($container),
]);

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();
$response = $dispatcher->dispatch($request);

$emitter = new Zend\HttpHandlerRunner\Emitter\SapiEmitter();
$emitter->emit($response);