<?php

namespace Sadl\Framework\Tests;

use PHPUnit\Framework\TestCase;
use Sadl\Framework\Session\Session;

class SessionTest extends TestCase
{
    // normally Sessions are mocked
    protected function setUp(): void
    {
        unset($_SESSION);
    }

    public function testAndSetupFlash(): void
    {
        $session = new Session();
        $session->setFlash('success', 'Great job!');
        $session->setFlash('error', 'Bad job!');
        $this->assertTrue($session->hasFlash('success'));
        $this->assertTrue($session->hasFlash('error'));
        $this->assertEquals(['Great job!'], $session->getFlash('success'));
        $this->assertEquals(['Bad job!'], $session->getFlash('error'));
        $this->assertEquals([], $session->getFlash('warning'));
    }
}