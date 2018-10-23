<?php

namespace Tests;

use App\Security;
use Mockery;
use PHPUnit\Framework\TestCase;

class SecurityTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testGenerateToken()
    {
        /** @var Security|\Mockery\MockInterface $security */
        $security = Mockery::mock(Security::class)->makePartial();
        $security
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getToken')
            ->andReturn('test token');

        $token = $security->generateToken();
        self::assertEquals('test token', $token);
    }

    public function testValidToken()
    {
        $_SESSION['token'] = 'test token';
        $security = new Security();
        $this->assertTrue($security->validToken('test token'));
    }
}
