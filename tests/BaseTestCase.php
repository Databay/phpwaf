<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class BaseTestCase extends TestCase
{
    private float $startTime;

    protected function setUp(): void
    {
        parent::setUp();

        $this->startTime = microtime(true);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $endTime = microtime(true);
        $this->assertLessThan( 0.01, ($endTime - $this->startTime), 'Took too long. From: ' . $this->startTime . ' to ' . $endTime);
    }

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

    protected static function getMethod(string|object $classOrObject, string $method): ReflectionMethod
    {
        $reflectionClass = new ReflectionClass($classOrObject);
        $method = $reflectionClass->getMethod($method);
        $method->setAccessible(true);
        return $method;
    }

    protected function replaceStaticMethod(string $className, string $methodName, $mock): void
    {
        $reflectionClass = new ReflectionClass($className);
        $reflectionMethod = $reflectionClass->getMethod($methodName);
        $reflectionMethod->setAccessible(true);
    }
}