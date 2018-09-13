<?php

// FastRouteでルーティング
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->get('/', 'top_handler');
    $r->get('/admin', 'admin_handler');
    $r->get('/posts', 'get_articles_handler');
    $r->get('/post/{id:\d+}', 'get_article_handler');
    $r->post('/post', 'post_article_handler');
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


$blog_title = $blog_title ?? '';
$error = null;
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $mode = dispatch($handler, $vars);
        $blog_title = $mode . $blog_title;
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

function dispatch($handler, array $vars): string
{
    $title = '';
    switch ($handler) {
        case 'admin_handler':
            $title = '管理モード ';
            break;
        case 'post_article_handler':
            $title = '投稿モード ';
            break;
        default:
            break;
    }
    return $title;
}