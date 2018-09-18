<?php

require_once '../vendor/autoload.php';

// FastRouteでルーティング
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->get('/', \App\Action\TopAction::class);
});

// RequestMethodとURIを取得する
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// クエリストリングを取得し、URIをデコードする
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// Routeを解決
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        dispatch($container, $handler, $vars);
        break;

    case FastRoute\Dispatcher::NOT_FOUND:
        echo "404 Not Found.";
        $error = true;
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo "405 Method Not Allowed.  allow only=" . json_encode($allowedMethods);
        $error = true;
        break;

    default:
        echo "500 Server Error.";
        $error = true;
        break;
}

use Psr\Container\ContainerInterface;

/**
 * @param ContainerInterface $container
 * @param $handler
 * @param array $vars
 * @return void
 * @throws Exception
 */
function dispatch(ContainerInterface $container, $handler, array $vars): void
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
    $instance->$method($vars);
}