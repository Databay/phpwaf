<?php

namespace App\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use App\Auto;
use PHPUnit\Framework\TestCase;

class TestAuto extends TestCase
{
    /**
     * @dataProvider blub
     */
    public function testGetSpeed(int $input, int $expected)
    {
        $auto = new Auto();
        $auto->setSpeed($input);
        $this->assertEquals($expected, $auto->getSpeed());
    }

    public static function blub(): array
    {
        return [
            [-1, -1],
            [200, 200],
            [300, 300],
            [400, 400],
            [500, 500],
            [600, 600],
        ];
    }
}