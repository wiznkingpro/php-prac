<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class SecurityTest extends TestCase
{
    public function testXssEscaping(): void
    {
        $dirty = '<script>alert("xss")</script>';
        $escaped = e($dirty);

        $this->assertStringNotContainsString('<script>', $escaped);
        $this->assertEquals('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', $escaped);
    }

    public function testCsrfTokenGeneration(): void
    {
        $token1 = csrf_token();
        $this->assertNotEmpty($token1);
        $this->assertEquals(64, strlen($token1));

        $token2 = csrf_token();
        $this->assertEquals($token1, $token2);

        $field = csrf_field();
        $this->assertStringContainsString('type="hidden"', $field);
        $this->assertStringContainsString('name="csrf_token"', $field);
        $this->assertStringContainsString('value="' . $token1 . '"', $field);
    }
}
