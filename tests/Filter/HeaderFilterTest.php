<?php

namespace App\Tests\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Filter\HeaderFilter;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class HeaderFilterTest extends BaseTestCase
{
    #[DataProvider('applyDataProvider')]
    public function testApply(array $input, bool $output): void
    {
        $request = new Request(null, null, null, null, null, null, null, $input['headers']);
        define('CONFIG', [
            'FILTER_HEADER_ACTIVE' => $input['FILTER_HEADER_ACTIVE'] ?? 'true',
            'FILTER_HEADER_PAYLOAD_FILES' => $input['FILTER_HEADER_PAYLOAD_FILES'] ?? '[]',
            'FILTER_HEADER_STRICT_MATCH' => $input['FILTER_HEADER_STRICT_MATCH'] ?? '[true]',
            'FILTER_HEADER_BLOCKING_TYPE' => AbstractFilter::BLOCKING_TYPE_WARNING,
        ]);

        if ($output === false) {
            $this->expectException(FilterException::class);
        }

        $this->assertNull((new HeaderFilter())->apply($request));
    }

    public static function applyDataProvider(): array
    {
        return [
            [['FILTER_HEADER_ACTIVE' => 'false', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'INVALID', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'false', 'FILTER_HEADER_PAYLOAD_FILES' => '[]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'INVALID', 'FILTER_HEADER_PAYLOAD_FILES' => '[]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'false', 'FILTER_HEADER_PAYLOAD_FILES' => '[]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'INVALID', 'FILTER_HEADER_PAYLOAD_FILES' => '[]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'false', 'FILTER_HEADER_PAYLOAD_FILES' => '', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'INVALID', 'FILTER_HEADER_PAYLOAD_FILES' => '', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'false', 'FILTER_HEADER_PAYLOAD_FILES' => '', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'INVALID', 'FILTER_HEADER_PAYLOAD_FILES' => '', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[test]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[test]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[test]', 'headers' => ['test']], false],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[test]', 'headers' => ['test']], false],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[prefixtestsuffix]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[prefixtestsuffix]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[prefixtestsuffix]', 'headers' => ['test']], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[prefixtestsuffix]', 'headers' => ['test']], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[test]', 'FILTER_HEADER_STRICT_MATCH' => '[false]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[test]', 'FILTER_HEADER_STRICT_MATCH' => '[false]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[test]', 'FILTER_HEADER_STRICT_MATCH' => '[false]', 'headers' => ['test']], false],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[test]', 'FILTER_HEADER_STRICT_MATCH' => '[false]', 'headers' => ['test']], false],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_HEADER_STRICT_MATCH' => '[false]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_HEADER_STRICT_MATCH' => '[false]', 'headers' => []], true],
            [['FILTER_HEADER_ACTIVE' => 'true', 'FILTER_HEADER_PAYLOAD_FILES' => '[test]', 'FILTER_HEADER_STRICT_MATCH' => '[false]', 'headers' => ['prefixtestsuffix']], false],
        ];
    }
}