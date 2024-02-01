<?php

namespace App\Tests\Filter;

use App\Entity\Request;
use App\Filter\URIFilter;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class URIFilterTest extends BaseTestCase
{
    #[DataProvider('applyDataProvider')]
    public function testApply(array $input, bool $output): void
    {
        $request = new Request(null, null, null, null, null, null, $input['SERVER'], null);
        define('CONFIG', [
            'FILTER_URI_ACTIVE' => $input['FILTER_URI_ACTIVE'] ?? 'true',
            'SERVER' => $input['SERVER'] ?? [],
            'FILTER_URI_CRITICAL_PAYLOAD_FILES' => $input['FILTER_URI_CRITICAL_PAYLOAD_FILES'] ?? '[]',
            'FILTER_URI_PAYLOAD_FILES' => $input['FILTER_URI_PAYLOAD_FILES'] ?? '[]',
            'FILTER_URI_STRICT_MATCH' => $input['FILTER_URI_STRICT_MATCH'] ?? 'true',
            'FILTER_URI_CRITICAL_STRICT_MATCH' => $input['FILTER_URI_CRITICAL_STRICT_MATCH'] ?? 'true',
        ]);
        $this->assertEquals($output, (new URIFilter())->apply($request));
    }

    // TODO: Add more tests to cover all the code paths
    public static function applyDataProvider(): array
    {
        return [
            [['SERVER' => ['REQUEST_URI' => '']], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'false'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'INVALID'], true],

            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_URI_PAYLOAD_FILES' => '[]'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'false', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_URI_PAYLOAD_FILES' => '[]'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'INVALID', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_URI_PAYLOAD_FILES' => '[]'], true],

            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_URI_PAYLOAD_FILES' => '[]'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'false', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_URI_PAYLOAD_FILES' => '[]'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'INVALID', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_URI_PAYLOAD_FILES' => '[]'], true],

            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_URI_PAYLOAD_FILES' => ''], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'false', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_URI_PAYLOAD_FILES' => ''], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'INVALID', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_URI_PAYLOAD_FILES' => ''], true],

            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_URI_PAYLOAD_FILES' => ''], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'false', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_URI_PAYLOAD_FILES' => ''], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'INVALID', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_URI_PAYLOAD_FILES' => ''], true],


            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[test]'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_URI_PAYLOAD_FILES' => '[test]'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[test]'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]'], true],

            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]'], false],
            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[test]'], false],
            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_URI_PAYLOAD_FILES' => '[test]'], false],
            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[test]'], false],
            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]'], false],

            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[prefixtestsuffix]'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_URI_PAYLOAD_FILES' => '[prefixtestsuffix]'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[prefixtestsuffix]'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]'], true],

            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]'], true],
            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[prefixtestsuffix]'], true],
            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_URI_PAYLOAD_FILES' => '[prefixtestsuffix]'], true],
            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[prefixtestsuffix]'], true],
            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]'], true],


            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[test]', 'FILTER_URI_STRICT_MATCH' => 'false'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_URI_PAYLOAD_FILES' => '[test]',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[test]', 'FILTER_URI_STRICT_MATCH' => 'false'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], true],

            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], false],
            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[test]', 'FILTER_URI_STRICT_MATCH' => 'false'], false],
            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_URI_PAYLOAD_FILES' => '[test]',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], false],
            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[test]', 'FILTER_URI_STRICT_MATCH' => 'false'], false],
            [['SERVER' => ['REQUEST_URI' => 'test'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], false],

            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_URI_STRICT_MATCH' => 'false'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_URI_PAYLOAD_FILES' => '[prefixtestsuffix]',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_URI_STRICT_MATCH' => 'false'], true],
            [['SERVER' => ['REQUEST_URI' => ''], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], true],

            [['SERVER' => ['REQUEST_URI' => 'prefixtestsuffix'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], false],
            [['SERVER' => ['REQUEST_URI' => 'prefixtestsuffix'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[test]', 'FILTER_URI_STRICT_MATCH' => 'false',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], false],
            [['SERVER' => ['REQUEST_URI' => 'prefixtestsuffix'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_URI_PAYLOAD_FILES' => '[test]',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], false],
            [['SERVER' => ['REQUEST_URI' => 'prefixtestsuffix'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_PAYLOAD_FILES' => '[test]', 'FILTER_URI_STRICT_MATCH' => 'false'], false],
            [['SERVER' => ['REQUEST_URI' => 'prefixtestsuffix'], 'FILTER_URI_ACTIVE' => 'true', 'FILTER_URI_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_URI_CRITICAL_STRICT_MATCH' => 'false'], false],
        ];
    }
}