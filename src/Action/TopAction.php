<?php

namespace App\Action;

use App\Domain\Top;
use App\Responder\TopResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TopAction
{
    private $domain;
    private $responder;

    public function __construct(Top $domain, TopResponder $responder)
    {
        $this->domain = $domain;
        $this->responder = $responder;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        return $this->responder->render($response, $this->domain->getDomain());
    }
}