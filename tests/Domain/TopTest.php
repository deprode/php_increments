<?php


use App\Domain\Top;
use PHPUnit\Framework\TestCase;

class TopTest extends TestCase
{

    public function testGetDomain()
    {
        $_SESSION = ['token' => 'test token'];
        $settings = ['title' => 'test title', 'subtitle' => 'test subtitle', 'author' => 'test author'];

        /** @var \PHPUnit\Framework\MockObject\MockObject|\App\Security $security */
        $security = $this->createMock(\App\Security::class);
        $security->expects($this->any())->method('generateToken')->willReturn('test token');

        /** @var \PHPUnit\Framework\MockObject\MockObject|\App\Repository\ArticleRepository $repository */
        $repository = $this->createMock(\App\Repository\ArticleRepository::class);
        $repository->expects($this->any())->method('fetchAll')->willReturn(['articles']);

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
