<?php

namespace App\Action;

use App\Domain\ArticleRepository;
use App\Security;
use Twig_Environment;

class TopAction
{
    private $repository;
    private $twig;
    private $security;
    private $setting;

    public function __construct(ArticleRepository $repository, Twig_Environment $twig, Security $security, array $setting)
    {
        $this->repository = $repository;
        $this->twig = $twig;
        $this->security = $security;
        $this->setting = $setting;
    }

    public function __invoke($param)
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

        echo $this->twig->render('index.twig', [
            'blog_title'    => $blog_title,
            'blog_subtitle' => $blog_subtitle,
            'posts'         => $articles,
            'token'         => $token,
            'author'        => $author,
        ]);
    }
}