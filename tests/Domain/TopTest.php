<?php

namespace Tests\Domain;

use App\Domain\Top;
use PHPUnit\Framework\TestCase;
use App\Security;
use App\Repository\ArticleRepository;

class TopTest extends TestCase
{

    public function testGetDomain()
    {
        $_SESSION = ['token' => 'test token'];
        $settings = ['title' => 'test title', 'subtitle' => 'test subtitle', 'author' => 'test author'];

        /** @var \PHPUnit\Framework\MockObject\MockObject|Security $security */
        $security = $this->createMock(Security::class);
        $security
            ->expects($this->any())
            ->method('generateToken')
            ->willReturn('test token');

        /** @var \PHPUnit\Framework\MockObject\MockObject|ArticleRepository $repository */
        $repository = $this->createMock(ArticleRepository::class);
        $repository
            ->expects($this->any())
            ->method('fetchAll')
            ->willReturn(['articles']);

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
