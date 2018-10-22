<?php

namespace Tests;

use App\Twig;
use PHPUnit\Framework\TestCase;
use \Mockery;

class TwigTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testRender()
    {
        /** @var \Twig\Environment|\Mockery\MockInterface $twig_env */
        $twig_env = Mockery::mock(\Twig_Environment::class);
        $twig_env
            ->shouldReceive('render')
            ->andReturn('<h1>Hello World!</h1>');

        $twig = new Twig($twig_env);
        $result = $twig->render('test.twig', []);

        $this->assertEquals('<h1>Hello World!</h1>', $result);
    }

    public function testSyntaxError()
    {
        /** @var \Twig\Environment|\Mockery\MockInterface $twig_env */
        $twig_env = Mockery::mock(\Twig_Environment::class);
        $twig_env
            ->shouldReceive('render')
            ->andThrowExceptions([new \Twig_Error_Syntax('エラー')]);

        $twig = new Twig($twig_env);
        $result = $twig->render('error.twig', []);
        $this->assertEquals('テンプレート構文エラー', $result);
    }
}
