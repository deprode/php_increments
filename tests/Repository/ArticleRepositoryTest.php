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
    /** @var \Mockery\MockInterface */
    protected $database;

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

        $this->database = Mockery::spy(Database::class);

        $this->repository = new ArticleRepository($this->database);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());

        Mockery::close();
    }

    public function testCreate()
    {
        $article1 = new Article();
        $article1->setTitle('ブログタイトル create');
        $article1->setBody('ブログの内容 create');
        $article1->setDate(new \DateTime('2018-12-12 12:12:12'));

        $this->database->shouldReceive('create')->andReturnTrue();

        $this->repository->create($article1);

        $this->database->shouldHaveReceived('create', [ArticleRepository::TABLE_NAME, [
            'title'      => 'ブログタイトル create',
            'body'       => 'ブログの内容 create',
            'created_at' => '2018-12-12 12:12:12'
        ]]);
    }

    public function testDelete()
    {
        $this->database->shouldReceive('delete')->andReturnTrue();

        $this->repository->delete(1);

        $this->database->shouldHaveReceived('delete', [ArticleRepository::TABLE_NAME, 1]);
    }

    public function testFetchAll()
    {
        $this->database->shouldReceive('fetchAll')->andReturn($this->articles);

        $this->assertEquals($this->articles, $this->repository->fetchAll());
    }

    public function testFetch()
    {
        $this->database->shouldReceive('fetch')->andReturn($this->articles[1]);

        $this->assertEquals($this->articles[1], $this->repository->fetch(1));
    }

    public function testUpdate()
    {
        $article1 = new Article();
        $article1->setTitle('ブログタイトル update');
        $article1->setBody('ブログの内容 update');
        $article1->setDate(new \DateTime('2018-12-12 12:12:12'));

        $this->database->shouldReceive('update')->andReturnTrue();

        $this->repository->update(1, $article1);
        $this->database->shouldHaveReceived('update', [
            ArticleRepository::TABLE_NAME,
            [
                'title'      => 'ブログタイトル update',
                'body'       => 'ブログの内容 update',
                'created_at' => '2018-12-12 12:12:12'
            ],
            1
        ]);
    }
}
