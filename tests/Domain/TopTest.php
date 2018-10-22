<?php

namespace Tests\Domain;

use App\Domain\Top;
use PHPUnit\Framework\TestCase;
use App\Security;
use App\Repository\ArticleRepository;
use \Mockery;

class TopTest extends TestCase
{

    public function testGetDomain()
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

        $top = new Top($repository, $security, $settings);

        $this->assertEquals([
            'blog_title'    => 'test title',
            'blog_subtitle' => 'test subtitle',
            'posts'         => ['articles'],
            'token'         => 'test token',
            'author'        => 'test author',
        ], $top->getDomain());
    }
}
