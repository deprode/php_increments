<?php

namespace Tests\Repository;

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

        $this->repository = new ArticleRepository();
        $this->repository->articles = $this->articles;
    }

    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testCreate()
    {
        $article3 = new Article();
        $article3->setTitle('ブログタイトル3');
        $article3->setBody('ブログの内容3');
        $article3->setDate(new \DateTime('2018-12-12 12:12:12'));

        $this->repository->create();
        $this->assertEquals($this->articles, $this->repository->fetchAll());

        $this->repository->create($article3);
        $this->articles[] = $article3;
        $this->assertEquals($this->articles, $this->repository->fetchAll());
    }

    public function testDelete()
    {
        $this->repository->delete();
        $this->assertEquals($this->articles, $this->repository->fetchAll());

        $this->repository->delete(1);
        array_splice($this->articles, 1);
        $this->assertEquals($this->articles, $this->repository->fetchAll());
    }

    public function testFetchAll()
    {
        $this->assertEquals($this->articles, $this->repository->fetchAll());
    }

    public function testFetch()
    {
        $this->assertEquals($this->articles[1], $this->repository->fetch(1));

        $this->assertEquals($this->articles[2], $this->repository->fetch(2));
    }

    public function testUpdate()
    {
        $this->repository->update(1);
        $this->assertEquals($this->articles[1], $this->repository->fetch(1));

        $article1 = new Article();
        $article1->setTitle('ブログタイトル update');
        $article1->setBody('ブログの内容 update');
        $article1->setDate(new \DateTime('2018-12-12 12:12:12'));

        $this->repository->update(1, $article1);
        $this->assertEquals($article1, $this->repository->fetch(1));
    }
}
