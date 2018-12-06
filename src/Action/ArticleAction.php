<?php


namespace App\Action;


use App\Domain\Article;
use App\Responder\ArticleResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ArticleAction
{
    private $domain;
    private $responder;

    public function __construct(Article $domain, ArticleResponder $responder)
    {
        $this->domain = $domain;
        $this->responder = $responder;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        return $this->responder->render($response, $this->domain->getArticle($request));
    }
}