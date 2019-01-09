<?php

namespace Tests\Domain;

use App\Domain\ArticleSave;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Security;
use Carbon\Carbon;
use Mockery;
use PHPUnit\Framework\TestCase;

class ArticleSaveTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2019-01-01 11:11:11'));
    }

    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
        Carbon::setTestNow();
    }

    public function testSaveArticle()
    {
        $_SESSION = ['token' => 'test token'];

        $security = new Security();
        /** @var \Mockery\MockInterface|ArticleRepository $repository */
        $repository = Mockery::spy(ArticleRepository::class);

        $domain = new ArticleSave($repository, $security, new Carbon());
        $domain->saveArticle([
            'title' => 'テスト',
            'body'  => '<h1>テストタイトル</h1>',
            'token' => 'test token'
        ]);

        $repository->shouldHaveReceived('create');

        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());
    }

    public function testGetTitle()
    {
        $_SESSION = ['token' => 'test token'];

        $security = new Security();
        /** @var \Mockery\MockInterface|ArticleRepository $repository */
        $repository = Mockery::mock(ArticleRepository::class);

        $domain = new ArticleSave($repository, $security, new Carbon());
        $class = new \ReflectionClass($domain);
        $method = $class->getMethod('getTitle');
        $method->setAccessible(true);
        $this->assertEquals('テスト', $method->invokeArgs($domain, ['テスト']));
    }

    public function testGetArticle()
    {
        $_SESSION = ['token' => 'test token'];

        $security = new Security();
        /** @var \Mockery\MockInterface|ArticleRepository $repository */
        $repository = Mockery::mock(ArticleRepository::class);

        $article = new Article();
        $article->setTitle('テスト');
        $article->setBody('テストタイトル');
        $article->setDate(Carbon::now());

        $domain = new ArticleSave($repository, $security, new Carbon());
        $class = new \ReflectionClass($domain);
        $method = $class->getMethod('getArticle');
        $method->setAccessible(true);
        $this->assertEquals($article, $method->invokeArgs($domain, ['テスト', 'テストタイトル']));
    }

    public function testSaveArticleNotTitle()
    {
        $_SESSION = ['token' => 'test token'];

        $security = new Security();
        /** @var \Mockery\MockInterface|ArticleRepository $repository */
        $repository = Mockery::spy(ArticleRepository::class);

        $domain = new ArticleSave($repository, $security, new Carbon());
        $class = new \ReflectionClass($domain);
        $method = $class->getMethod('getTitle');
        $method->setAccessible(true);
        $this->assertEquals('無題', $method->invokeArgs($domain, ['']));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage 正規の画面から投稿してください
     */
    public function testTokenError()
    {
        $_SESSION = ['token' => 'invalid token'];

        $security = new Security();
        /** @var \Mockery\MockInterface|ArticleRepository $repository */
        $repository = Mockery::mock(ArticleRepository::class);
        $domain = new ArticleSave($repository, $security, new Carbon());

        $domain->saveArticle([
            'title' => 'テスト',
            'body'  => '<h1>テストタイトル</h1>',
            'token' => 'test token'
        ]);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage タイトルが長すぎます。100字以内にしてください。
     */
    public function testTitleError()
    {
        $_SESSION = ['token' => 'test token'];

        $security = new Security();
        /** @var \Mockery\MockInterface|ArticleRepository $repository */
        $repository = Mockery::mock(ArticleRepository::class);
        $domain = new ArticleSave($repository, $security, new Carbon());

        $title = bin2hex(random_bytes(100));
        $domain->saveArticle([
            'title' => $title,
            'body'  => '<h1>テストタイトル</h1>',
            'token' => 'test token'
        ]);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage 投稿内容がありません。0字以上にしてください。
     */
    public function testBodyEmptyError()
    {
        $_SESSION = ['token' => 'test token'];

        $security = new Security();
        /** @var \Mockery\MockInterface|ArticleRepository $repository */
        $repository = Mockery::mock(ArticleRepository::class);
        $domain = new ArticleSave($repository, $security, new Carbon());

        $domain->saveArticle([
            'title' => 'test',
            'body'  => '',
            'token' => 'test token'
        ]);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage 投稿内容が長すぎます。8000字以内にしてください。
     */
    public function testBodyError()
    {
        $_SESSION = ['token' => 'test token'];

        $security = new Security();
        /** @var \Mockery\MockInterface|ArticleRepository $repository */
        $repository = Mockery::mock(ArticleRepository::class);
        $domain = new ArticleSave($repository, $security, new Carbon());

        $body = bin2hex(random_bytes(8000));
        $domain->saveArticle([
            'title' => 'テスト',
            'body'  => $body,
            'token' => 'test token'
        ]);
    }
}
