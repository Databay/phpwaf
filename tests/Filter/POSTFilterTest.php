<?php

namespace App\Tests\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterException;
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
        $request = new Request(null, null, $input['POST'], null, null, null, $input['SERVER'], null);
        define('CONFIG', [
            'SERVER' => $input['SERVER'] ?? [],
            'POST' => $input['POST'] ?? [],
            'FILTER_POST_ACTIVE' => $input['FILTER_POST_ACTIVE'] ?? 'true',
            'FILTER_POST_PAYLOAD_FILES' => $input['FILTER_POST_PAYLOAD_FILES'] ?? '[]',
            'FILTER_POST_STRICT_MATCH' => $input['FILTER_POST_STRICT_MATCH'] ?? '[true]',
            'FILTER_POST_BLOCKING_TYPE' => AbstractFilter::BLOCKING_TYPE_WARNING,
        ]);

        if ($output === false) {
            $this->expectException(FilterException::class);
        }

        $this->assertNull((new POSTFilter())->apply($request));
    }

    public static function applyDataProvider(): array
    {
        return [
            [['SERVER' => ['REQUEST_METHOD' => 'OTHER'], 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'false', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'INVALID', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'false', 'FILTER_POST_PAYLOAD_FILES' => '[]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'INVALID', 'FILTER_POST_PAYLOAD_FILES' => '[]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'false', 'FILTER_POST_PAYLOAD_FILES' => '[]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'INVALID', 'FILTER_POST_PAYLOAD_FILES' => '[]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'false', 'FILTER_POST_PAYLOAD_FILES' => '', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'INVALID', 'FILTER_POST_PAYLOAD_FILES' => '', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'false', 'FILTER_POST_PAYLOAD_FILES' => '', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'INVALID', 'FILTER_POST_PAYLOAD_FILES' => '', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[test]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[test]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[test]', 'POST' => ['test']], false],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[test]', 'POST' => ['test']], false],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'POST' => ['test']], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'POST' => ['test']], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[test]', 'FILTER_POST_STRICT_MATCH' => '[false]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[test]', 'FILTER_POST_STRICT_MATCH' => '[false]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[test]', 'FILTER_POST_STRICT_MATCH' => '[false]', 'POST' => ['test']], false],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[test]', 'FILTER_POST_STRICT_MATCH' => '[false]', 'POST' => ['test']], false],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_POST_STRICT_MATCH' => '[false]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_POST_STRICT_MATCH' => '[false]', 'POST' => []], true],
            [['SERVER' => ['REQUEST_METHOD' => 'POST'], 'FILTER_POST_ACTIVE' => 'true', 'FILTER_POST_PAYLOAD_FILES' => '[test]', 'FILTER_POST_STRICT_MATCH' => '[false]', 'POST' => ['prefixtestsuffix']], false],
        ];
    }
}