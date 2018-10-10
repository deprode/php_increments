<?php


use App\Twig;
use PHPUnit\Framework\TestCase;

class TwigTest extends TestCase
{
    public function testRender()
    {
        /** @var \Twig\Environment|\PHPUnit\Framework\MockObject\MockObject $twig_env */
        $twig_env = $this->createMock(Twig_Environment::class);
        $twig_env
            ->expects($this->any())
            ->method('render')
            ->willReturn('<h1>Hello World!</h1>');

        $twig = new Twig($twig_env);
        $result = $twig->render('test.twig', []);

        $this->assertEquals('<h1>Hello World!</h1>', $result);
    }

    public function testSyntaxError()
    {
        /** @var \Twig\Environment|\PHPUnit\Framework\MockObject\MockObject $twig_env */
        $twig_env = $this->createMock(Twig_Environment::class);
        $twig_env
            ->expects($this->any())
            ->method('render')
            ->willThrowException(new \Twig_Error_Syntax('エラー'));

        $twig = new Twig($twig_env);
        $result = $twig->render('error.twig', []);
        $this->assertEquals('テンプレート構文エラー', $result);
    }
}
