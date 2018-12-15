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

        $title = $this->getTitle($params['title']);
        if (mb_strlen($title) > 100) {
            throw new \Exception('タイトルが長すぎます。100字以内にしてください。');
        }
        $body = $params['body'];
        if (empty($body)) {
            throw new \Exception('投稿内容がありません。0字以上にしてください。');
        } else if (mb_strlen($body) > 8000) {
            throw new \Exception('投稿内容が長すぎます。8000字以内にしてください。');
        }

        $article = $this->getArticle($title, $body, 'now');
        $this->articles->create($article);
    }

    protected function getTitle(string $title): string
    {
        return (empty($title) ? '無題' : $title);
    }


    protected function getArticle(string $title, string $body, string $date): Article
    {
        $article = new Article();
        $article->setTitle($title);
        $article->setBody($body);
        $article->setDate(new \DateTime($date));

        return $article;
    }
}