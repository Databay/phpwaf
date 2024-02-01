<?php

namespace App\Tests\Filter;

use App\Entity\Request;
use App\Filter\RequestFilter;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class RequestFilterTest extends BaseTestCase
{
    #[DataProvider('applyDataProvider')]
    public function testApply(array $input, bool $output): void
    {
        $request = new Request($input['request'], null, null, null, null, null, null, null);
        define('CONFIG', [
            'FILTER_REQUEST_ACTIVE' => $input['FILTER_REQUEST_ACTIVE'] ?? 'true',
            'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => $input['FILTER_REQUEST_CRITICAL_PAYLOAD_FILES'] ?? '[]',
            'FILTER_REQUEST_PAYLOAD_FILES' => $input['FILTER_REQUEST_PAYLOAD_FILES'] ?? '[]',
            'FILTER_REQUEST_STRICT_MATCH' => $input['FILTER_REQUEST_STRICT_MATCH'] ?? 'true',
            'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => $input['FILTER_REQUEST_CRITICAL_STRICT_MATCH'] ?? 'true',
        ]);
        $this->assertEquals($output, (new RequestFilter())->apply($request));
    }

    public static function applyDataProvider(): array
    {
        return [
            [['FILTER_REQUEST_ACTIVE' => 'false', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'INVALID', 'request' => []], true],

            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_REQUEST_PAYLOAD_FILES' => '[]', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'false', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_REQUEST_PAYLOAD_FILES' => '[]', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'INVALID', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_REQUEST_PAYLOAD_FILES' => '[]', 'request' => []], true],

            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_REQUEST_PAYLOAD_FILES' => '[]', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'false', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_REQUEST_PAYLOAD_FILES' => '[]', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'INVALID', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_REQUEST_PAYLOAD_FILES' => '[]', 'request' => []], true],

            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_REQUEST_PAYLOAD_FILES' => '', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'false', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_REQUEST_PAYLOAD_FILES' => '', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'INVALID', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_REQUEST_PAYLOAD_FILES' => '', 'request' => []], true],

            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_REQUEST_PAYLOAD_FILES' => '', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'false', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_REQUEST_PAYLOAD_FILES' => '', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'INVALID', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_REQUEST_PAYLOAD_FILES' => '', 'request' => []], true],


            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]', 'request' => []], true],

            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]', 'request' => ['test']], false],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]', 'request' => ['test']], false],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]', 'request' => ['test']], false],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]', 'request' => ['test']], false],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]', 'request' => ['test']], false],

            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_REQUEST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'request' => []], true],

            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'request' => ['test']], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'request' => ['test']], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_REQUEST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'request' => ['test']], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'request' => ['test']], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'request' => ['test']], true],


            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]', 'FILTER_REQUEST_STRICT_MATCH' => 'false', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]', 'FILTER_REQUEST_STRICT_MATCH' => 'false', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => []], true],

            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => ['test']], false],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]', 'FILTER_REQUEST_STRICT_MATCH' => 'false', 'request' => ['test']], false],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => ['test']], false],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]', 'FILTER_REQUEST_STRICT_MATCH' => 'false', 'request' => ['test']], false],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => ['test']], false],

            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_REQUEST_STRICT_MATCH' => 'false', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_REQUEST_PAYLOAD_FILES' => '[prefixtestsuffix]',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_REQUEST_STRICT_MATCH' => 'false', 'request' => []], true],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => []], true],

            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => ['prefixtestsuffix']], false],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]', 'FILTER_REQUEST_STRICT_MATCH' => 'false',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => ['prefixtestsuffix']], false],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => ['prefixtestsuffix']], false],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_PAYLOAD_FILES' => '[test]', 'FILTER_REQUEST_STRICT_MATCH' => 'false', 'request' => ['prefixtestsuffix']], false],
            [['FILTER_REQUEST_ACTIVE' => 'true', 'FILTER_REQUEST_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_REQUEST_CRITICAL_STRICT_MATCH' => 'false', 'request' => ['prefixtestsuffix']], false],
        ];
    }
}