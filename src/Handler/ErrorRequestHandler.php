<?php

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorRequestHandler implements RequestHandlerInterface
{
    use \Middlewares\Utils\Traits\HasResponseFactory;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $error = $request->getAttribute('error');
        $response = $this->createResponse($error->getCode());
        $response->getBody()->write($error->getMessage());

        return $response->withHeader('Content-Type', 'text/plain');
    }
}