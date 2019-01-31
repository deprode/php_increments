<?php

namespace App\Domain;


use App\Repository\ArticleRepository;
use App\Security;
use Psr\Http\Message\ServerRequestInterface;

class NewArticle
{
    private $repository;
    private $security;
    private $setting;

    public function __construct(ArticleRepository $repository, Security $security, array $setting)
    {
        $this->repository = $repository;
        $this->security = $security;
        $this->setting = $setting;
    }

    public function __invoke(ServerRequestInterface $request): array
    {
        $blog_title = $this->setting['title'];
        $blog_subtitle = $this->setting['subtitle'];
        $author = $this->setting['author'];

        // フォームにCSRF対策
        $token = $this->security->generateToken();

        return [
            'blog_title'    => $blog_title,
            'blog_subtitle' => $blog_subtitle,
            'author'        => $author,
            'token'         => $token
        ];
    }
}