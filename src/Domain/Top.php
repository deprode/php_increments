<?php

namespace App\Domain;

use App\Security;
use App\Interfaces\RepositoryInterface;

class Top
{
    private $repository;
    private $security;
    private $setting;

    public function __construct(RepositoryInterface $repository, Security $security, array $setting)
    {
        $this->repository = $repository;
        $this->setting = $setting;
        $this->security = $security;
    }

    public function getDomain(): array
    {
        $blog_title = $this->setting['title'];
        $blog_subtitle = $this->setting['subtitle'];
        $author = $this->setting['author'];

        // フォームにCSRF対策
        $token = $this->security->generateToken();

        // ブログ記事の取得
        $articles = $this->repository->fetchAll();

        return [
            'blog_title'    => $blog_title,
            'blog_subtitle' => $blog_subtitle,
            'posts'         => $articles,
            'token'         => $token,
            'author'        => $author,
        ];
    }
}