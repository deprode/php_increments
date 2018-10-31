<?php

namespace Tests\Domain;

use App\Domain\ArticleSave;
use App\Repository\ArticleRepository;
use App\Security;
use Mockery;
use PHPUnit\Framework\TestCase;

class ArticleSaveTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testSaveArticle()
    {
        $_SESSION = ['token' => 'test token'];

        $security = new Security();
        /** @var \Mockery\MockInterface|ArticleRepository $repository */
        $repository = Mockery::spy(ArticleRepository::class);

        $domain = new ArticleSave($repository, $security);
        $domain->saveArticle([
            'title' => 'テスト',
            'body'  => '<h1>テストタイトル</h1>',
            'token' => 'test token'
        ]);

        $repository->shouldHaveReceived('create');

        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage 正規の画面から投稿してください
     */
    public function testTokenError()
    {
        $_SESSION = ['token' => 'invalid token'];

        $security = new Security();
        $repository = new ArticleRepository();
        $domain = new ArticleSave($repository, $security);

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
        $repository = new ArticleRepository();
        $domain = new ArticleSave($repository, $security);

        $title = bin2hex(random_bytes(100));
        $domain->saveArticle([
            'title' => $title,
            'body'  => '<h1>テストタイトル</h1>',
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
        $repository = new ArticleRepository();
        $domain = new ArticleSave($repository, $security);

        $body = bin2hex(random_bytes(8000));
        $domain->saveArticle([
            'title' => 'テスト',
            'body'  => $body,
            'token' => 'test token'
        ]);
    }
}
