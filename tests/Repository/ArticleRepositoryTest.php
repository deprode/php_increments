<?php

namespace Tests\Repository;

use App\Database;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

class ArticleRepositoryTest extends TestCase
{
    protected $articles;
    /** @var ArticleRepository */
    protected $repository;

    protected function setUp()
    {
        parent::setUp();

        $article1 = new Article();
        $article1->setTitle('ブログタイトル');
        $article1->setBody('ブログの内容');
        $article1->setDate(new \DateTime('2018-10-10 10:10:10'));
        $article2 = new Article();
        $article2->setTitle('ブログタイトル2');
        $article2->setBody('ブログの内容2');
        $article2->setDate(new \DateTime('2018-11-11 11:11:11'));

        $this->articles = [
            1 => $article1,
            2 => $article2,
        ];

        $database = Mockery::mock(Database::class);

        $database->shouldReceive('create')->andReturnTrue();
        $database->shouldReceive('update')->andReturnTrue();
        $database->shouldReceive('delete')->andReturnTrue();
        $database->shouldReceive('fetch')->andReturn($this->articles[1]);
        $database->shouldReceive('fetchAll')->andReturn($this->articles);

        $this->repository = new ArticleRepository($database);
    }

    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testCreate()
    {
        $article1 = new Article();
        $article1->setTitle('ブログタイトル update');
        $article1->setBody('ブログの内容 update');
        $article1->setDate(new \DateTime('2018-12-12 12:12:12'));

        $this->repository->create($article1);

        $this->assertTrue(true);
    }

    public function testDelete()
    {
        $this->repository->delete(1);
        $this->assertTrue(true);
    }

    public function testFetchAll()
    {
        $this->assertEquals($this->articles, $this->repository->fetchAll());
    }

    public function testFetch()
    {
        $this->assertEquals($this->articles[1], $this->repository->fetch(1));
    }

    public function testUpdate()
    {
        $article1 = new Article();
        $article1->setTitle('ブログタイトル update');
        $article1->setBody('ブログの内容 update');
        $article1->setDate(new \DateTime('2018-12-12 12:12:12'));

        $this->repository->update(1, $article1);
        $this->assertTrue(true);
    }
}
