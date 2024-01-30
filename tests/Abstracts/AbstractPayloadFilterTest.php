<?php

namespace App\Tests\Abstracts {
    use App\Abstracts\AbstractPayloadFilter;
    use App\Entity\Request;
    use App\Tests\BaseTestCase;
    use PHPUnit\Framework\Attributes\DataProvider;
    use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

    #[RunTestsInSeparateProcesses]
    class AbstractPayloadFilterTest extends BaseTestCase
    {
        private AbstractPayloadFilter $abstractPayloadFilter;

        protected function setUp(): void
        {
            parent::setUp();

            $this->abstractPayloadFilter = new class extends AbstractPayloadFilter {
                public $filterName;

                public $criticalMatch;

                public function apply(Request $request): bool
                {
                    return true;
                }
            };
        }

        #[DataProvider('valueFoundInPayloadDataProvider')]
        public function testValueFoundInPayload(array $input, bool $output): void
        {
            $method = self::getMethod($this->abstractPayloadFilter, 'valueFoundInPayload');
            $this->assertEquals($output, $method->invokeArgs($this->abstractPayloadFilter, $input));
        }

        public static function valueFoundInPayloadDataProvider(): array
        {
            return [
                [['test', ['test' => 'test'], true], true],
                [['test', ['test' => 'test'], false], true],
                [['test', [], true], false],
                [['test', [], false], false],
                [[['test'], ['test' => 'test'], true], true],
                [[['test'], ['test' => 'test'], false], true],
                [[['test'], [], true], false],
                [[['test'], [], false], false],
                [['prefixtestsuffix', ['test' => 'test'], true], false],
                [['prefixtestsuffix', ['test' => 'test'], false], true],
                [['prefixtestsuffix', [], true], false],
                [['prefixtestsuffix', [], false], false],
                [[['prefixtestsuffix'], ['test' => 'test'], true], false],
                [[['prefixtestsuffix'], ['test' => 'test'], false], true],
                [[['prefixtestsuffix'], [], true], false],
                [[['prefixtestsuffix'], [], false], false],
                [['test', ['prefixtestsuffix' => 'prefixtestsuffix'], true], false],
                [['test', ['prefixtestsuffix' => 'prefixtestsuffix'], false], false],
                [['test', [], true], false],
                [['test', [], false], false],
                [[['test'], ['prefixtestsuffix' => 'prefixtestsuffix'], true], false],
                [[['test'], ['prefixtestsuffix' => 'prefixtestsuffix'], false], false],
                [[['test'], [], true], false],
                [[['test'], [], false], false],
            ];
        }

        #[DataProvider('recursiveArrayTraversalDataProvider')]
        public function testRecursiveArrayTraversal(array $input, bool $output): void
        {
            $method = self::getMethod($this->abstractPayloadFilter, 'recursiveArrayTraversal');
            $this->assertEquals($output, $method->invokeArgs($this->abstractPayloadFilter, $input));
        }

        public static function recursiveArrayTraversalDataProvider(): array
        {
            return [
                [[['test'], ['test' => 'test'], true], true],
                [[['test'], ['test' => 'test'], false], true],
                [[['test'], [], true], false],
                [[['test'], [], false], false],
                [[['prefixtestsuffix'], ['test' => 'test'], true], false],
                [[['prefixtestsuffix'], ['test' => 'test'], false], true],
                [[['prefixtestsuffix'], [], true], false],
                [[['prefixtestsuffix'], [], false], false],

                [[[['test']], ['test' => 'test'], true], true],
                [[[['test']], ['test' => 'test'], false], true],
                [[[['test']], [], true], false],
                [[[['test']], [], false], false],
                [[[['prefixtestsuffix']], ['test' => 'test'], true], false],
                [[[['prefixtestsuffix']], ['test' => 'test'], false], true],
                [[[['prefixtestsuffix']], [], true], false],
                [[[['prefixtestsuffix']], [], false], false],
            ];
        }

        #[DataProvider('handlePayloadDataProvider')]
        public function testHandleCriticalPayload(array $input, bool $output): void
        {
            $this->abstractPayloadFilter->filterName = 'ABSTRACTPAYLOAD';
            define('CONFIG', [
                'FILTER_ABSTRACTPAYLOAD_CRITICAL_PAYLOAD_FILES' => $input['payloadFileString'],
                'FILTER_ABSTRACTPAYLOAD_CRITICAL_STRICT_MATCH' => $input['strictMatch'],
            ]);
            $method = self::getMethod($this->abstractPayloadFilter, 'handleCriticalPayload');
            $this->assertEquals($output, $method->invokeArgs($this->abstractPayloadFilter, [$input['value']]));
        }

        #[DataProvider('handlePayloadDataProvider')]
        public function testHandleRegularPayload(array $input, bool $output): void
        {
            $this->abstractPayloadFilter->filterName = 'ABSTRACTPAYLOAD';
            define('CONFIG', [
                'FILTER_ABSTRACTPAYLOAD_PAYLOAD_FILES' => $input['payloadFileString'],
                'FILTER_ABSTRACTPAYLOAD_STRICT_MATCH' => $input['strictMatch'],
            ]);
            $method = self::getMethod($this->abstractPayloadFilter, 'handleRegularPayload');
            $this->assertEquals($output, $method->invokeArgs($this->abstractPayloadFilter, [$input['value']]));
        }

        public static function handlePayloadDataProvider(): array
        {
            return [
                [['value' => 'test', 'payloadFileString' => '[]', 'strictMatch' => 'true'], true],
                [['value' => 'test', 'payloadFileString' => '[test]', 'strictMatch' => 'true'], false],
                [['value' => 'test', 'payloadFileString' => '[prefixtestsuffix]', 'strictMatch' => 'true'], true],
                [['value' => 'test', 'payloadFileString' => '[INVALID]', 'strictMatch' => 'true'], true],
                [['value' => ['test'], 'payloadFileString' => '[]', 'strictMatch' => 'true'], true],
                [['value' => ['test'], 'payloadFileString' => '[test]', 'strictMatch' => 'true'], false],
                [['value' => ['test'], 'payloadFileString' => '[prefixtestsuffix]', 'strictMatch' => 'true'], true],
                [['value' => ['test'], 'payloadFileString' => '[INVALID]', 'strictMatch' => 'true'], true],
                [['value' => 'prefixtestsuffix', 'payloadFileString' => '[]', 'strictMatch' => 'true'], true],
                [['value' => 'prefixtestsuffix', 'payloadFileString' => '[test]', 'strictMatch' => 'true'], true],
                [['value' => 'prefixtestsuffix', 'payloadFileString' => '[prefixtestsuffix]', 'strictMatch' => 'true'], false],
                [['value' => 'prefixtestsuffix', 'payloadFileString' => '[INVALID]', 'strictMatch' => 'true'], true],
                [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[]', 'strictMatch' => 'true'], true],
                [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[test]', 'strictMatch' => 'true'], true],
                [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[prefixtestsuffix]', 'strictMatch' => 'true'], false],
                [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[INVALID]', 'strictMatch' => 'true'], true],

                [['value' => 'test', 'payloadFileString' => '[]', 'strictMatch' => 'false'], true],
                [['value' => 'test', 'payloadFileString' => '[test]', 'strictMatch' => 'false'], false],
                [['value' => 'test', 'payloadFileString' => '[prefixtestsuffix]', 'strictMatch' => 'false'], true],
                [['value' => 'test', 'payloadFileString' => '[INVALID]', 'strictMatch' => 'false'], true],
                [['value' => ['test'], 'payloadFileString' => '[]', 'strictMatch' => 'false'], true],
                [['value' => ['test'], 'payloadFileString' => '[test]', 'strictMatch' => 'false'], false],
                [['value' => ['test'], 'payloadFileString' => '[prefixtestsuffix]', 'strictMatch' => 'false'], true],
                [['value' => ['test'], 'payloadFileString' => '[INVALID]', 'strictMatch' => 'false'], true],
                [['value' => 'prefixtestsuffix', 'payloadFileString' => '[]', 'strictMatch' => 'false'], true],
                [['value' => 'prefixtestsuffix', 'payloadFileString' => '[test]', 'strictMatch' => 'false'], false],
                [['value' => 'prefixtestsuffix', 'payloadFileString' => '[prefixtestsuffix]', 'strictMatch' => 'false'], false],
                [['value' => 'prefixtestsuffix', 'payloadFileString' => '[INVALID]', 'strictMatch' => 'false'], true],
                [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[]', 'strictMatch' => 'false'], true],
                [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[test]', 'strictMatch' => 'false'], false],
                [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[prefixtestsuffix]', 'strictMatch' => 'false'], false],
                [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[INVALID]', 'strictMatch' => 'false'], true],
            ];
        }
    }
}