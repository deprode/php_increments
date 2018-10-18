<?php

namespace Tests\Middleware;

use Middlewares\Utils\Dispatcher;
use App\Middleware\SecurityHeaderMiddleware;
use PHPUnit\Framework\TestCase;

class SecurityHeaderMiddlewareTest extends TestCase
{
    public function testProcess()
    {
        $response = Dispatcher::run([
            new SecurityHeaderMiddleware()
        ]);

        $this->assertSame('SAMEORIGIN', $response->getHeader('X-Frame-Options')[0]);
        $this->assertSame('1; mode=block', $response->getHeader('X-XSS-Protection')[0]);
        $this->assertSame('nosniff', $response->getHeader('X-Content-Type-Options')[0]);
    }
}
