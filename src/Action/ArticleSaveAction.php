<?php

namespace App\Action;

use App\Domain\ArticleSave;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ArticleSaveAction
{
    /** @var \App\Domain\ArticleSave */
    private $domain;

    public function __construct(ArticleSave $domain)
    {
        $this->domain = $domain;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        try {
            $this->domain->saveArticle($request->getParsedBody());
        } catch (\Exception $e) {
            // TODO: エラー画面の表示
            $response->withStatus(503)->getBody()->write($e->getMessage());
            return $response;
        }

        return $response->withStatus(303)->withHeader('Location', $request->getUri()->getPath());
    }
}