<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected function assertArrayContainsInstanceOf(string|object $expected, array $actual): void
    {
        if (is_object($expected)) {
            $expected = $expected::class;
        }

        foreach ($actual as $value) {
            if ($value instanceof $expected) {
                $this->assertTrue(true);
                return;
            }
        }

        $this->fail();
    }
}