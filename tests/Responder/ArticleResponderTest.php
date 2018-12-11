<?php

namespace Tests\Responder;

use App\Interfaces\ViewInterface;
use App\Responder\ArticleResponder;
use Mockery;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response;

class ArticleResponderTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testRender()
    {
        $mock_view = Mockery::mock(ViewInterface::class);
        $mock_view
            ->shouldReceive('render')
            ->andReturn('<h1>test template render</h1>');

        $response = new Response();

        $responder = new ArticleResponder($mock_view);
        $response = $responder->render($response, []);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/html; charset=utf-8', $response->getHeader('Content-Type')[0]);
        $this->assertEquals('<h1>test template render</h1>', $response->getBody());
    }
}
