<?php

namespace Tests\Domain;

use App\Domain\NewArticle;
use App\Repository\ArticleRepository;
use App\Security;
use Mockery;
use PHPUnit\Framework\TestCase;

class NewArticleTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function test__invoke()
    {
        $_SESSION = ['token' => 'test token'];
        $settings = ['title' => 'test title', 'subtitle' => 'test subtitle', 'author' => 'test author'];

        /** @var \Mockery\MockInterface|Security $security */
        $security = Mockery::mock(Security::class);
        $security
            ->shouldReceive('generateToken')
            ->andReturn('test token');

        /** @var \Mockery\MockInterface|ArticleRepository $repository */
        $repository = Mockery::mock(ArticleRepository::class);
        $repository
            ->shouldReceive('fetchAll')
            ->andReturn(['articles']);

        $top = new NewArticle($repository, $security, $settings);

        $this->assertEquals([
            'blog_title'    => 'test title',
            'blog_subtitle' => 'test subtitle',
            'token'         => 'test token',
            'author'        => 'test author',
        ], ($top)());
    }
}
