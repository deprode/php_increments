<?php

namespace App\Action;

use App\Domain\ArticleRepository;
use App\Responder\TopResponder;
use App\Security;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TopAction
{
    private $repository;
    private $responder;
    private $security;
    private $setting;

    public function __construct(ArticleRepository $repository, TopResponder $responder, Security $security, array $setting)
    {
        $this->repository = $repository;
        $this->responder = $responder;
        $this->security = $security;
        $this->setting = $setting;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $blog_title = $this->setting['title'];
        $blog_subtitle = $this->setting['subtitle'];
        $author = $this->setting['author'];

        // フォームにCSRF対策
        $token = $this->security->generateToken();
        $this->security->checkToken($token, $_SESSION['token']);
        $this->security->outputSecureHeader();

        // ブログ記事の取得
        $articles = $this->repository->getArticles();

        $response->withStatus(200);
        $response->getBody()->write($this->responder->render([
            'blog_title'    => $blog_title,
            'blog_subtitle' => $blog_subtitle,
            'posts'         => $articles,
            'token'         => $token,
            'author'        => $author,
        ]));
        $response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');

        return $response;
    }
}