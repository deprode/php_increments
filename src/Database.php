<?php


namespace App;

use \Doctrine\DBAL\Connection;
use App\Interfaces\RepositoryInterface;

class Database implements RepositoryInterface
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    public function fetch(string $table = '', $class = '', int $id = 0)
    {
        $sql = 'SELECT * FROM ' . $table . ' WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $stmt->setFetchMode(\Doctrine\DBAL\FetchMode::CUSTOM_OBJECT, $class);
        return $stmt->fetch();
    }

    public function fetchAll(string $table = '', $class = ''): array
    {
        $sql = 'SELECT * FROM ' . $table;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\Doctrine\DBAL\FetchMode::CUSTOM_OBJECT, $class);
    }

    public function create(string $table = '', array $params = []): int
    {
        if (empty($table) || empty($params)) {
            return 0;
        }

        return $this->connection->insert($table, $params);
    }

    public function update(string $table = '', array $params = [], int $id = 0): int
    {
        if (empty($table) || empty($id) || empty($params)) {
            return 0;
        }

        return $this->connection->update($table, $params, ['id' => $id]);
    }

    public function delete(string $table = '', int $id = 0): int
    {
        if (empty($table) || empty($id)) {
            return 0;
        }

        return $this->connection->delete($table, ['id' => $id]);
    }
}