<?php

namespace App\Tests\Filter {
    use App\Entity\Request;
    use App\Filter\GETFilter;
    use App\Tests\BaseTestCase;
    use PHPUnit\Framework\Attributes\DataProvider;
    use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

    #[RunTestsInSeparateProcesses]
    class GETFilterTest extends BaseTestCase
    {
        #[DataProvider('applyDataProvider')]
        public function testApply(array $input, bool $output): void
        {
            $request = new Request(null, $input['GET'], null, null, null, null, $input['SERVER'], null);
            define('CONFIG', [
                'SERVER' => $input['SERVER'] ?? [],
                'GET' => $input['GET'] ?? [],
                'FILTER_GET_ACTIVE' => $input['FILTER_GET_ACTIVE'] ?? 'true',
                'FILTER_GET_CRITICAL_PAYLOAD_FILES' => $input['FILTER_GET_CRITICAL_PAYLOAD_FILES'] ?? '[]',
                'FILTER_GET_PAYLOAD_FILES' => $input['FILTER_GET_PAYLOAD_FILES'] ?? '[]',
                'FILTER_GET_STRICT_MATCH' => $input['FILTER_GET_STRICT_MATCH'] ?? 'true',
                'FILTER_GET_CRITICAL_STRICT_MATCH' => $input['FILTER_GET_CRITICAL_STRICT_MATCH'] ?? 'true',
            ]);
            $this->assertEquals($output, (new GETFilter())->apply($request));
        }

        public static function applyDataProvider(): array
        {
            return [
                [['SERVER' => ['REQUEST_METHOD' => 'OTHER'], 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'false', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'INVALID', 'GET' => []], true],

                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_GET_PAYLOAD_FILES' => '[]', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'false', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_GET_PAYLOAD_FILES' => '[]', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'INVALID', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_GET_PAYLOAD_FILES' => '[]', 'GET' => []], true],

                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_GET_PAYLOAD_FILES' => '[]', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'false', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_GET_PAYLOAD_FILES' => '[]', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'INVALID', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_GET_PAYLOAD_FILES' => '[]', 'GET' => []], true],

                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_GET_PAYLOAD_FILES' => '', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'false', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_GET_PAYLOAD_FILES' => '', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'INVALID', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[]', 'FILTER_GET_PAYLOAD_FILES' => '', 'GET' => []], true],

                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_GET_PAYLOAD_FILES' => '', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'false', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_GET_PAYLOAD_FILES' => '', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'INVALID', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '', 'FILTER_GET_PAYLOAD_FILES' => '', 'GET' => []], true],


                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[test]', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_GET_PAYLOAD_FILES' => '[test]', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[test]', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]', 'GET' => []], true],

                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]', 'GET' => ['test']], false],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[test]', 'GET' => ['test']], false],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_GET_PAYLOAD_FILES' => '[test]', 'GET' => ['test']], false],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[test]', 'GET' => ['test']], false],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]', 'GET' => ['test']], false],

                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[prefixtestsuffix]', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_GET_PAYLOAD_FILES' => '[prefixtestsuffix]', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[prefixtestsuffix]', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'GET' => []], true],

                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'GET' => ['test']], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[prefixtestsuffix]', 'GET' => ['test']], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_GET_PAYLOAD_FILES' => '[prefixtestsuffix]', 'GET' => ['test']], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[prefixtestsuffix]', 'GET' => ['test']], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'GET' => ['test']], true],


                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[test]', 'FILTER_GET_STRICT_MATCH' => 'false', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_GET_PAYLOAD_FILES' => '[test]',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[test]', 'FILTER_GET_STRICT_MATCH' => 'false', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => []], true],

                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => ['test']], false],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[test]', 'FILTER_GET_STRICT_MATCH' => 'false', 'GET' => ['test']], false],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_GET_PAYLOAD_FILES' => '[test]',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => ['test']], false],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[test]', 'FILTER_GET_STRICT_MATCH' => 'false', 'GET' => ['test']], false],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => ['test']], false],

                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_GET_STRICT_MATCH' => 'false', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_GET_PAYLOAD_FILES' => '[prefixtestsuffix]',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[prefixtestsuffix]', 'FILTER_GET_STRICT_MATCH' => 'false', 'GET' => []], true],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[prefixtestsuffix]',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => []], true],

                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => ['prefixtestsuffix']], false],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[test]', 'FILTER_GET_STRICT_MATCH' => 'false',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => ['prefixtestsuffix']], false],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]', 'FILTER_GET_PAYLOAD_FILES' => '[test]',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => ['prefixtestsuffix']], false],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_PAYLOAD_FILES' => '[test]', 'FILTER_GET_STRICT_MATCH' => 'false', 'GET' => ['prefixtestsuffix']], false],
                [['SERVER' => ['REQUEST_METHOD' => 'GET'], 'FILTER_GET_ACTIVE' => 'true', 'FILTER_GET_CRITICAL_PAYLOAD_FILES' => '[test]',  'FILTER_GET_CRITICAL_STRICT_MATCH' => 'false', 'GET' => ['prefixtestsuffix']], false],
            ];
        }
    }
}