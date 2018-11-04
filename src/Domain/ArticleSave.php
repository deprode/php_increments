<?php


namespace App\Domain;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Security;

class ArticleSave
{
    /** @var \App\Repository\ArticleRepository */
    private $articles;

    /** @var Security */
    private $security;

    public function __construct(ArticleRepository $articles, Security $security)
    {
        $this->articles = $articles;
        $this->security = $security;
    }

    /**
     * @param array $params
     * @throws \Exception
     */
    public function saveArticle(array $params)
    {
        if (!$this->security->validToken($params['token'])) {
            throw new \Exception('正規の画面から投稿してください');
        }

        $title = $params['title'] ?? '無題';
        if (mb_strlen($title) > 100) {
            throw new \Exception('タイトルが長すぎます。100字以内にしてください。');
        }
        $body = $params['body'] ?? '内容なし';
        if (mb_strlen($body) > 8000) {
            throw new \Exception('投稿内容が長すぎます。8000字以内にしてください。');
        }

        $article = new Article();
        $article->setTitle($title);
        $article->setBody($body);
        $article->setDate(new \DateTime('now'));

        $this->articles->create($article);
    }
}