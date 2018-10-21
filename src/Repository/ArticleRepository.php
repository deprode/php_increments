<?php

namespace App\Repository;


use App\Entity\Article;
use App\Interfaces\RepositoryInterface;

class ArticleRepository implements RepositoryInterface
{
    private $articles;

    public function __construct()
    {
        $article1 = new Article();
        $article1->setTitle('ブログタイトル');
        $article1->setBody('ブログの内容');
        $article1->setDate(new \DateTime('2018-10-10 10:10:10'));
        $article2 = new Article();
        $article2->setTitle('ブログタイトル2');
        $article2->setBody('ブログの内容2');
        $article2->setDate(new \DateTime('2018-11-11 11:11:11'));

        $this->articles = [
            '1' => $article1,
            '2' => $article2,
        ];
    }

    public function fetch(int $id = 0)
    {
        return $this->articles[$id];
    }

    public function fetchAll()
    {
        // TODO: DBALでブログ記事取得

        return $this->articles;
    }

    public function create(Article $article = null)
    {
        if (empty($article)) {
            return;
        }

        $this->articles[] = $article;
    }

    public function update(int $id = 0, Article $article = null)
    {
        if (empty($id)) {
            return;
        }

        $this->articles[$id] = $article;

    }

    public function delete(int $id = 0)
    {
        if (empty($id)) {
            return;
        }

        array_splice($this->articles, $id);
    }
}