<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @param ContainerInterface $container
 * @param $handler
 * @param ServerRequestInterface $request
 * @param ResponseInterface $response
 * @return ResponseInterface
 * @throws Exception
 */
function dispatch(ContainerInterface $container, $handler, ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
{
    if (mb_strpos($handler, ':')) {
        list($key, $method) = explode(':', $handler);
    } else {
        $key = $handler;
        $method = '__invoke';
    }

    if (!$container->has($key)) {
        throw new Exception('クラスが存在しません');
    }
    $instance = $container->get($key);

    if (!method_exists($instance, $method)) {
        throw new Exception('メソッドが存在しません');
    }

    return $instance->$method($request, $response);
}


// FastRouteでルーティング
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->get('/', \App\Action\TopAction::class);
});

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

// Routeを解決
$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

$response = new Zend\Diactoros\Response();
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $response = dispatch($container, $handler, $request, $response);
        break;

    case FastRoute\Dispatcher::NOT_FOUND:
        $response = $response->withHeader('Content-Type', 'text/plain; charset=utf-8');
        $response = $response->withStatus(404);
        $response->getBody()->write("404 Not Found.");
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response = $response->withHeader('Content-Type', 'text/plain; charset=utf-8');
        $response = $response->withStatus(405);
        $response->getBody()->write("405 Method Not Allowed. allow only=" . json_encode($allowedMethods));
        break;

    default:
        $response = $response->withHeader('Content-Type', 'text/plain; charset=utf-8');
        $response = $response->withStatus(500);
        $response->getBody()->write("500 Server Error.");
        break;
}


$emitter = new Zend\HttpHandlerRunner\Emitter\SapiEmitter();
$emitter->emit($response);