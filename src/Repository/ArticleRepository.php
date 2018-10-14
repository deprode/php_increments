<?php

namespace App\Repository;


use App\Interfaces\RepositoryInterface;

class ArticleRepository implements RepositoryInterface
{
    private $articles;

    public function __construct()
    {
        $this->articles = [
            '1' => [
                'title' => 'ブログタイトル',
                'body'  => 'ブログの内容',
                'date'  => '2018-10-10 10:10:10'
            ],
            '2' => [
                'title' => 'ブログタイトル2',
                'body'  => 'ブログの内容2',
                'date'  => '2018-11-11 11:11:11'
            ],
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

    public function create(array $article = [])
    {
        if (empty($article)) {
            return;
        }

        $this->articles[] = $article;
    }

    public function update(int $id = 0, array $article = [])
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