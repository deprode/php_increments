<?php


namespace App\Domain;

use App\Entity\Article;
use App\Interfaces\RepositoryInterface;
use App\Security;

class ArticleSave
{
    /** @var \App\Repository\ArticleRepository */
    private $articles;

    /** @var Security */
    private $security;

    public function __construct(RepositoryInterface $articles, Security $security)
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

        $article = new Article();
        $article->setTitle($params['title'] ?? '無題');
        $article->setBody($params['body'] ?? '内容なし');
        $article->setDate(new \DateTime('now'));

        $this->articles->create($article);
    }
}