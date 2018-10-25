<?php

namespace App\Repository;


use App\Entity\Article;
use App\Interfaces\RepositoryInterface;

class ArticleRepository implements RepositoryInterface
{
    protected $articles;

    public function __construct()
    {
        $this->articles = [];
    }

    /** TODO: DBAL追加時に消す */
    public function __set($name, $value)
    {
        if ($name === 'articles') {
            $this->articles = $value;
        }
    }

    public function fetch(int $id = 0): Article
    {
        return $this->articles[$id];
    }

    public function fetchAll(): array
    {
        // TODO: DBALでブログ記事取得

        return $this->articles;
    }

    public function create(Article $article = null): void
    {
        if (empty($article)) {
            return;
        }

        $this->articles[] = $article;
    }

    public function update(int $id = 0, Article $article = null): void
    {
        if (empty($id) || empty($article)) {
            return;
        }

        $this->articles[$id] = $article;
    }

    public function delete(int $id = 0): void
    {
        if (empty($id)) {
            return;
        }

        array_splice($this->articles, $id);
    }
}