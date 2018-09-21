<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Psr\Container\ContainerInterface;


/**
 * @param ContainerInterface $container
 * @param $handler
 * @param array $vars
 * @return array
 * @throws Exception
 */
function dispatch(ContainerInterface $container, $handler, array $vars): array
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
    return $instance->$method($vars);
}


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

$response_status = 0;
$response_body = '';
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        list($response_status, $response_body) = dispatch($container, $handler, $vars);
        break;

    case FastRoute\Dispatcher::NOT_FOUND:
        $response_body = "404 Not Found.";
        $response_status = 404;
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        $response_body = "405 Method Not Allowed.  allow only=" . json_encode($allowedMethods);
        $response_status = 405;
        break;

    default:
        $response_body = "500 Server Error.";
        $response_status = 500;
        break;
}

http_response_code($response_status);
header('Content-Type: text/html; charset=utf-8');
echo $response_body;