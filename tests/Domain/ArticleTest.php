<?php

namespace Tests\Domain;

use App\Domain\Article;
use App\Repository\ArticleRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class ArticleTest extends TestCase
{

    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testGetArticle()
    {
        $_SESSION = ['token' => 'test token'];
        $settings = ['title' => 'test title', 'subtitle' => 'test subtitle', 'author' => 'test author'];

        /** @var \Mockery\MockInterface|ServerRequestInterface $request */
        $request = Mockery::mock(ServerRequestInterface::class);
        $request
            ->shouldReceive('getAttribute')
            ->andReturn(1);

        $post = new \App\Entity\Article();
        $post->setTitle('title');
        $post->setBody('body');
        $post->setDate(new \DateTime('2018-10-10 10:10:10'));

        /** @var \Mockery\MockInterface|ArticleRepository $repository */
        $repository = Mockery::mock(ArticleRepository::class);
        $repository
            ->shouldReceive('fetch')
            ->andReturn($post);

        $article = new Article($repository, $settings);

        $this->assertEquals([
            'blog_title'    => 'test title',
            'blog_subtitle' => 'test subtitle',
            'author'        => 'test author',
            'post'          => $post,
        ], $article->getArticle($request));

    }
}
