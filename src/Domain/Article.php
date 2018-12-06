<?php


namespace App\Domain;


use App\Repository\ArticleRepository;
use Psr\Http\Message\ServerRequestInterface;

class Article
{
    private $repository;
    private $setting;

    public function __construct(ArticleRepository $repository, array $setting)
    {
        $this->repository = $repository;
        $this->setting = $setting;
    }

    public function getArticle(ServerRequestInterface $request): array
    {
        $blog_title = $this->setting['title'];
        $blog_subtitle = $this->setting['subtitle'];
        $author = $this->setting['author'];

        // ブログ記事の取得
        $id = $request->getAttribute('id');
        $post = $this->repository->fetch($id);

        return [
            'blog_title'    => $blog_title,
            'blog_subtitle' => $blog_subtitle,
            'post'          => $post,
            'author'        => $author,
        ];
    }
}