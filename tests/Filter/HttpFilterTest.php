<?php

namespace App\Tests\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Filter\HttpFilter;
use App\Service\ConfigLoader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HttpFilterTest extends TestCase
{
    private float $startTime;

	public function setUp(): void
    {
		if(!defined("CONFIG")){
			define('CONFIG', ConfigLoader::loadConfig());
		}

        $this->startTime = microtime(true);
    }

    public function tearDown(): void
    {
        $endTime = microtime(true);

        $this->assertLessThan( 0.001, ($endTime - $this->startTime), 'Took too long. From: ' . $this->startTime . ' to ' . $endTime);
    }

    #[DataProvider('applyDataProvider')]
    public function testApply($input, bool $output): void
    {
        $request = new Request(null, null, null, null, null, null, $input);
        $this->assertEquals($output, (new HttpFilter())->apply($request));
    }

    public static function applyDataProvider(): array
    {
        return [
            [['HTTPS' => 'on'], true],
            [['HTTPS' => 'off'], false],
            [['HTTPS' => ''], false],
            [['HTTPS' => null], true],
            [['HTTPS' => 1], false],
            [['HTTPS' => 0.1], false],
            [['HTTPS' => 0.1], false],
            [['HTTPS' => true], false],
            [['HTTPS' => false], false],
            [['HTTPS' => []], false],
            [['HTTPS' => (object) []], false],
            [[], true],
            ['', true],
            [null, true],
            [1, true],
            [0.1, true],
            [true, true],
            [false, true],
            [(object) [], true],
        ];
    }

    #[DataProvider('getBlockingTypeDataProvider')]
    public function testGetBlockingType($input, $output): void
    {

    }

    public static function getBlockingTypeDataProvider(): array
    {
        return [
            ['', AbstractFilter::BLOCKING_TYPE_WARNING],
            ['test', AbstractFilter::BLOCKING_TYPE_WARNING],
            [1, AbstractFilter::BLOCKING_TYPE_WARNING],
            [0.1, AbstractFilter::BLOCKING_TYPE_WARNING],
            [true, AbstractFilter::BLOCKING_TYPE_WARNING],
            [false, AbstractFilter::BLOCKING_TYPE_WARNING],
            [[], AbstractFilter::BLOCKING_TYPE_WARNING],
            [(object) [], AbstractFilter::BLOCKING_TYPE_WARNING],
            [AbstractFilter::BLOCKING_TYPE_WARNING, AbstractFilter::BLOCKING_TYPE_WARNING],
        ];
    }
}