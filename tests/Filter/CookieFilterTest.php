<?php

namespace App\Tests\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Filter\CookieFilter;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class CookieFilterTest extends BaseTestCase
{
    #[DataProvider('applyDataProvider')]
    public function testApply(array $input, bool $output): void
    {
        $request = new Request(null, null, null, null, $input['cookies'], null, null, null);
        define('CONFIG', [
            'FILTER_COOKIE_ACTIVE' => $input['FILTER_COOKIE_ACTIVE'] ?? 'true',
            'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => $input['FILTER_COOKIE_CRITICAL_PAYLOAD_FILES'] ?? '[]',
            'FILTER_COOKIE_PAYLOAD_FILES' => $input['FILTER_COOKIE_PAYLOAD_FILES'] ?? '[]',
            'FILTER_COOKIE_STRICT_MATCH' => $input['FILTER_COOKIE_STRICT_MATCH'] ?? '[true]',
            'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => $input['FILTER_COOKIE_CRITICAL_STRICT_MATCH'] ?? '[true]',
            'FILTER_COOKIE_CRITICAL_BLOCKING_TYPE' => AbstractFilter::BLOCKING_TYPE_WARNING,
            'FILTER_COOKIE_BLOCKING_TYPE' => AbstractFilter::BLOCKING_TYPE_WARNING,
        ]);

        if ($output === false) {
            $this->expectException(FilterException::class);
        }

        $this->assertNull((new CookieFilter())->apply($request));
    }

    public static function applyDataProvider(): array
    {
        return [
            [['FILTER_COOKIE_ACTIVE' => 'false', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'INVALID', 'cookies' => []], true],

            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_COOKIE_PAYLOAD_FILES' => '[]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'false', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_COOKIE_PAYLOAD_FILES' => '[]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'INVALID', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_COOKIE_PAYLOAD_FILES' => '[]', 'cookies' => []], true],

            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_COOKIE_PAYLOAD_FILES' => '[]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'false', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_COOKIE_PAYLOAD_FILES' => '[]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'INVALID', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_COOKIE_PAYLOAD_FILES' => '[]', 'cookies' => []], true],

            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_COOKIE_PAYLOAD_FILES' => '', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'false', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_COOKIE_PAYLOAD_FILES' => '', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'INVALID', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_COOKIE_PAYLOAD_FILES' => '', 'cookies' => []], true],

            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_COOKIE_PAYLOAD_FILES' => '', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'false', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_COOKIE_PAYLOAD_FILES' => '', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'INVALID', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_COOKIE_PAYLOAD_FILES' => '', 'cookies' => []], true],


            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]', 'cookies' => []], true],

            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]', 'cookies' => ['test']], false],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]', 'cookies' => ['test']], false],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]', 'cookies' => ['test']], false],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]', 'cookies' => ['test']], false],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]', 'cookies' => ['test']], false],

            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[prefixtestsuffix]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_COOKIE_PAYLOAD_FILES' => '[prefixtestsuffix]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[prefixtestsuffix]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'cookies' => []], true],

            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'cookies' => ['test']], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[prefixtestsuffix]', 'cookies' => ['test']], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_COOKIE_PAYLOAD_FILES' => '[prefixtestsuffix]', 'cookies' => ['test']], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[prefixtestsuffix]', 'cookies' => ['test']], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'cookies' => ['test']], true],


            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]', 'FILTER_COOKIE_STRICT_MATCH' => '[false]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]', 'FILTER_COOKIE_STRICT_MATCH' => '[false]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => []], true],

            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => ['test']], false],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]', 'FILTER_COOKIE_STRICT_MATCH' => '[false]', 'cookies' => ['test']], false],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => ['test']], false],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]', 'FILTER_COOKIE_STRICT_MATCH' => '[false]', 'cookies' => ['test']], false],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => ['test']], false],

            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_COOKIE_STRICT_MATCH' => '[false]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_COOKIE_PAYLOAD_FILES' => '[prefixtestsuffix]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_COOKIE_STRICT_MATCH' => '[false]', 'cookies' => []], true],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => []], true],

            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => ['prefixtestsuffix']], false],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]', 'FILTER_COOKIE_STRICT_MATCH' => '[false]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => ['prefixtestsuffix']], false],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => ['prefixtestsuffix']], false],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_PAYLOAD_FILES' => '[test]', 'FILTER_COOKIE_STRICT_MATCH' => '[false]', 'cookies' => ['prefixtestsuffix']], false],
            [['FILTER_COOKIE_ACTIVE' => 'true', 'FILTER_COOKIE_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_COOKIE_CRITICAL_STRICT_MATCH' => '[false]', 'cookies' => ['prefixtestsuffix']], false],
        ];
    }
}