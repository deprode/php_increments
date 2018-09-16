<?php

namespace App\Action;

use App\Security;
use Twig_Environment;

class TopAction
{
    private $twig;
    private $security;
    private $setting;

    public function __construct(Twig_Environment $twig, Security $security, array $setting)
    {
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

        // TODO: DBALでブログ記事取得
        $posts = [
            '0' => [
                'title' => 'ブログタイトル',
                'body'  => 'ブログの内容',
                'date'  => '2018-10-10 10:10:10'
            ],
            '1' => [
                'title' => 'ブログタイトル2',
                'body'  => 'ブログの内容2',
                'date'  => '2018-11-11 11:11:11'
            ],
        ];


        echo $this->twig->render('index.twig', [
            'blog_title'    => $blog_title,
            'blog_subtitle' => $blog_subtitle,
            'posts'         => $posts,
            'token'         => $token,
            'author'        => $author,
        ]);
    }
}