<?php

namespace Tests;

use App\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $_SESSION = [];
    }

    public function testGet()
    {
        $session = new Session();
        $_SESSION['test'] = 'test string';

        $this->assertEquals('test string', $session->get('test'));

        $this->assertNull($session->get('none'));
    }

    public function testSet()
    {
        $session = new Session();
        $session->set('test', 'test string');

        $this->assertEquals('test string', $session->get('test'));
    }
}
