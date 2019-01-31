<?php


namespace App\Action;


use App\Domain\NewArticle;
use App\Responder\NewArticleResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NewArticleAction
{
    private $domain;
    private $responder;

    public function __construct(NewArticle $domain, NewArticleResponder $responder)
    {
        $this->domain = $domain;
        $this->responder = $responder;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        return $this->responder->render($response, ($this->domain)($request));
    }
}