<?php

namespace Tests;

use App\Database;
use App\Entity\Article;
use Mockery;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    /** @var \Doctrine\DBAL\Connection|\Mockery\MockInterface connection */
    public $connection;

    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    protected function setUp()
    {
        parent::setUp();
        $this->connection = Mockery::mock(\Doctrine\DBAL\Connection::class);
    }

    public function testDelete()
    {
        $this->connection->shouldReceive('delete')->andReturn(1);

        $database = new Database($this->connection);
        $this->assertEquals(1, $database->delete('Test', 1));
    }

    public function testCreate()
    {
        $this->connection->shouldReceive('insert')->andReturn(1);

        $database = new Database($this->connection);
        $this->assertEquals(1, $database->create('Test', ['title' => 'test']));
    }

    public function testUpdate()
    {
        $this->connection->shouldReceive('update')->andReturn(1);

        $database = new Database($this->connection);
        $this->assertEquals(1, $database->update('Test', ['title' => 'test'], 1));
    }

    public function testFetch()
    {
        $stmt = Mockery::mock(\Doctrine\DBAL\Driver\Statement::class);
        $stmt->shouldReceive('bindValue')->andReturn();
        $stmt->shouldReceive('execute')->andReturn();
        $stmt->shouldReceive('setFetchMode')->andReturn();
        $stmt->shouldReceive('fetch')->andReturn(['fetch' => 'fetched!']);
        $this->connection->shouldReceive('prepare')->andReturn($stmt);

        $database = new Database($this->connection);
        $this->assertEquals(['fetch' => 'fetched!'], $database->fetch('Test', Article::class, 1));
    }

    public function testFetchAll()
    {
        $stmt = Mockery::mock(\Doctrine\DBAL\Driver\Statement::class);
        $stmt->shouldReceive('execute')->andReturn();
        $stmt->shouldReceive('fetchAll')->andReturn(['fetch' => 'fetched!']);
        $this->connection->shouldReceive('createQueryBuilder')->andReturn($stmt);
        $this->connection->shouldReceive('prepare')->andReturn($stmt);

        $database = new Database($this->connection);
        $this->assertEquals(['fetch' => 'fetched!'], $database->fetchAll('Test', Article::class));
    }
}
