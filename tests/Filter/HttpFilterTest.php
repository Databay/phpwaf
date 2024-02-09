<?php

namespace App\Tests\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Filter\HttpFilter;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class HttpFilterTest extends BaseTestCase
{
    #[DataProvider('applyDataProvider')]
    public function testApply(array $input, bool $output): void
    {
        define('CONFIG', ['FILTER_HTTP_ACTIVE' => $input['FILTER_HTTP_ACTIVE'], 'FILTER_HTTP_CRITICAL_BLOCKING_TYPE' => AbstractFilter::BLOCKING_TYPE_WARNING, 'FILTER_HTTP_BLOCKING_TYPE' => AbstractFilter::BLOCKING_TYPE_WARNING]);
        $request = new Request(null, null, null, null, null, null, $input['HTTPS'], null);

        if ($output === false) {
            $this->expectException(FilterException::class);
        }

        $this->assertNull((new HttpFilter())->apply($request));
    }

    public static function applyDataProvider(): array
    {
        return [
            [['FILTER_HTTP_ACTIVE' => 'true', 'HTTPS' => ['HTTPS' => 'on']], true],
            [['FILTER_HTTP_ACTIVE' => 'true', 'HTTPS' => ['HTTPS' => 'off']], false],
            [['FILTER_HTTP_ACTIVE' => 'true', 'HTTPS' => ['HTTPS' => '']], false],
            [['FILTER_HTTP_ACTIVE' => 'true', 'HTTPS' => ['HTTPS' => null]], false],
            [['FILTER_HTTP_ACTIVE' => 'true', 'HTTPS' => ['HTTPS' => 1]], false],
            [['FILTER_HTTP_ACTIVE' => 'true', 'HTTPS' => ['HTTPS' => 0.1]], false],
            [['FILTER_HTTP_ACTIVE' => 'true', 'HTTPS' => ['HTTPS' => 0.1]], false],
            [['FILTER_HTTP_ACTIVE' => 'true', 'HTTPS' => ['HTTPS' => true]], false],
            [['FILTER_HTTP_ACTIVE' => 'true', 'HTTPS' => ['HTTPS' => false]], false],
            [['FILTER_HTTP_ACTIVE' => 'true', 'HTTPS' => ['HTTPS' => []]], false],
            [['FILTER_HTTP_ACTIVE' => 'true', 'HTTPS' => ['HTTPS' => (object) []]], false],
            [['FILTER_HTTP_ACTIVE' => 'false', 'HTTPS' => ['HTTPS' => 'on']], true],
            [['FILTER_HTTP_ACTIVE' => 'false', 'HTTPS' => ['HTTPS' => 'off']], true],
            [['FILTER_HTTP_ACTIVE' => 'false', 'HTTPS' => ['HTTPS' => '']], true],
            [['FILTER_HTTP_ACTIVE' => 'false', 'HTTPS' => ['HTTPS' => null]], true],
            [['FILTER_HTTP_ACTIVE' => 'false', 'HTTPS' => ['HTTPS' => 1]], true],
            [['FILTER_HTTP_ACTIVE' => 'false', 'HTTPS' => ['HTTPS' => 0.1]], true],
            [['FILTER_HTTP_ACTIVE' => 'false', 'HTTPS' => ['HTTPS' => 0.1]], true],
            [['FILTER_HTTP_ACTIVE' => 'false', 'HTTPS' => ['HTTPS' => true]], true],
            [['FILTER_HTTP_ACTIVE' => 'false', 'HTTPS' => ['HTTPS' => false]], true],
            [['FILTER_HTTP_ACTIVE' => 'false', 'HTTPS' => ['HTTPS' => []]], true],
            [['FILTER_HTTP_ACTIVE' => 'false', 'HTTPS' => ['HTTPS' => (object) []]], true],
        ];
    }

    #[DataProvider('getBlockingTypeDataProvider')]
    public function testGetBlockingType($input, $output): void
    {
        define('CONFIG', ['FILTER_HTTP_BLOCKING_TYPE' => $input['FILTER_HTTP_BLOCKING_TYPE']]);
        $this->assertEquals($output, (new HttpFilter())->getBlockingType());
    }

    public static function getBlockingTypeDataProvider(): array
    {
        return [
            [['FILTER_HTTP_BLOCKING_TYPE' => 'WARNING'], 'WARNING'],
            [['FILTER_HTTP_BLOCKING_TYPE' => 'REJECT'], 'REJECT'],
            [['FILTER_HTTP_BLOCKING_TYPE' => 'TIMEOUT'], 'TIMEOUT'],
            [['FILTER_HTTP_BLOCKING_TYPE' => 'CRITICAL'], 'CRITICAL'],
            [['FILTER_HTTP_BLOCKING_TYPE' => 'INVALID'], 'WARNING'],
        ];
    }
}