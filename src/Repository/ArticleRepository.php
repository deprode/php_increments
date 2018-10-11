<?php

namespace App\Repository;


class ArticleRepository
{
    public function __construct()
    {
    }

    public function getArticles()
    {
        // TODO: DBALでブログ記事取得
        $articles = [
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

        return $articles;
    }
}