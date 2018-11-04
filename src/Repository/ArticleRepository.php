<?php

namespace App\Repository;


use App\Entity\Article;
use App\Interfaces\RepositoryInterface;

class ArticleRepository implements RepositoryInterface
{
    private $connection;

    public function __construct(\Doctrine\DBAL\Connection $connection)
    {
        $this->connection = $connection;
    }


    public function fetch(int $id = 0): Article
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $sql = $queryBuilder->select('*')->from('Articles')->where('id = :id')->getSQL();
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $stmt->setFetchMode(\Doctrine\DBAL\FetchMode::CUSTOM_OBJECT, Article::class);
        return $stmt->fetch();
    }

    public function fetchAll(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $stmt = $queryBuilder->select('*')->from('Articles')->execute();
        return $stmt->fetchAll(\Doctrine\DBAL\FetchMode::CUSTOM_OBJECT, Article::class);
    }

    public function create(Article $article = null): void
    {
        if (empty($article)) {
            return;
        }

        $this->connection->insert(
            'Articles',
            [
                'title'      => $article->getTitle(),
                'body'       => $article->getBody(),
                'created_at' => $article->getDate()->format('Y-m-d H:i:s')
            ]
        );
    }

    public function update(int $id = 0, Article $article = null): void
    {
        if (empty($id) || empty($article)) {
            return;
        }

        $this->connection->update(
            'Articles',
            ['title' => ':title', 'body' => ':body', 'created_at' => ':date'],
            ['id' => $id]
        );
    }

    public function delete(int $id = 0): void
    {
        if (empty($id)) {
            return;
        }

        $this->connection->delete('Articles', ['id' => $id]);
    }
}