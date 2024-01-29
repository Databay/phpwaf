<?php

namespace App\Tests\Filter;

use App\Entity\Request;
use App\Filter\POSTFilter;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class POSTFilterTest extends BaseTestCase
{
    #[DataProvider('applyDataProvider')]
    public function testApply(array $input, bool $output): void
    {
        $request = new Request(null, null, $input['POST'], null, null, null, $input['SERVER']);
        define('CONFIG', [
            'SERVER' => $input['SERVER'] ?? [],
            'POST' => $input['POST'] ?? [],
            'FILTER_POST_ACTIVE' => $input['FILTER_POST_ACTIVE'] ?? 'true',
            'FILTER_POST_CRITICAL_PAYLOAD_FILES' => $input['FILTER_POST_CRITICAL_PAYLOAD_FILES'] ?? '[]',
            'FILTER_POST_PAYLOAD_FILES' => $input['FILTER_POST_PAYLOAD_FILES'] ?? '[]',
        ]);
        $this->assertEquals($output, (new POSTFilter())->apply($request));
    }

    // TODO: Add more tests to cover all the code paths
    public static function applyDataProvider(): array
    {
        return [
            [['SERVER' => ['REQUEST_METHOD' => 'OTHER']], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'false'], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'INVALID'], true],

            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_POST_PAYLOAD_FILES' => '[]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'false', 'FILTER_POST_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_POST_PAYLOAD_FILES' => '[]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'INVALID', 'FILTER_POST_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_POST_PAYLOAD_FILES' => '[]', 'POST' => []], true],

            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_POST_PAYLOAD_FILES' => '[]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'false', 'FILTER_POST_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_POST_PAYLOAD_FILES' => '[]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'INVALID', 'FILTER_POST_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_POST_PAYLOAD_FILES' => '[]', 'POST' => []], true],

            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_POST_PAYLOAD_FILES' => '', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'false', 'FILTER_POST_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_POST_PAYLOAD_FILES' => '', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'INVALID', 'FILTER_POST_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_POST_PAYLOAD_FILES' => '', 'POST' => []], true],

            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_POST_PAYLOAD_FILES' => '', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'false', 'FILTER_POST_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_POST_PAYLOAD_FILES' => '', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'INVALID', 'FILTER_POST_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_POST_PAYLOAD_FILES' => '', 'POST' => []], true],
        ];
    }
}