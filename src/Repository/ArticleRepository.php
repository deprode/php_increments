<?php

namespace App\Repository;


use App\Database;
use App\Entity\Article;
use App\Interfaces\RepositoryInterface;

class ArticleRepository
{
    /** @var Database */
    private $database;
    const TABLE_NAME = 'Articles';

    public function __construct(RepositoryInterface $database)
    {
        $this->database = $database;
    }

    public function fetch(int $id = 0): Article
    {
        return $this->database->fetch(self::TABLE_NAME, Article::class, $id);
    }

    public function fetchAll(): array
    {
        return $this->database->fetchAll(self::TABLE_NAME, Article::class);
    }

    public function create(Article $article = null): void
    {
        $this->database->create(self::TABLE_NAME, [
            'title'      => $article->getTitle(),
            'body'       => $article->getBody(),
            'created_at' => $article->getDate()->format('Y-m-d H:i:s')
        ]);
    }

    public function update(int $id = 0, Article $article = null): void
    {
        $this->database->update(self::TABLE_NAME,
            [
                'title'      => $article->getTitle(),
                'body'       => $article->getBody(),
                'created_at' => $article->getDate()->format('Y-m-d H:i:s')
            ],
            $id
        );
    }

    public function delete(int $id = 0): void
    {
        $this->database->delete(self::TABLE_NAME, $id);
    }
}