<?php

namespace Abstracts;

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
        ];
    }

    #[DataProvider('handleCriticalPayloadDataProvider')]
    public function testHandleCriticalPayload(array $input, bool $output): void
    {
        $this->markTestIncomplete();

        $this->abstractPayloadFilter->filterName = 'ABSTRACTPAYLOAD';
        define('CONFIG', ['FILTER_ABSTRACTPAYLOAD_CRITICAL_PAYLOAD_FILES' => $input['payloadFileString']]);
        $method = self::getMethod($this->abstractPayloadFilter, 'handleCriticalPayload');
        $this->assertEquals($output, $method->invokeArgs($this->abstractPayloadFilter, [$input['value']]));
    }

    public static function handleCriticalPayloadDataProvider(): array
    {
        return [
            [['value' => 'test', 'payloadFileString' => '[]'], true],
            [['value' => 'test', 'payloadFileString' => '[test]'], true],
            [['value' => 'test', 'payloadFileString' => '[prefixtestsuffix]'], true],
            [['value' => 'test', 'payloadFileString' => '[INVALID]'], true],
            [['value' => ['test'], 'payloadFileString' => '[]'], true],
            [['value' => ['test'], 'payloadFileString' => '[test]'], true],
            [['value' => ['test'], 'payloadFileString' => '[prefixtestsuffix]'], true],
            [['value' => ['test'], 'payloadFileString' => '[INVALID]'], true],
            [['value' => 'prefixtestsuffix', 'payloadFileString' => '[]'], true],
            [['value' => 'prefixtestsuffix', 'payloadFileString' => '[test]'], true],
            [['value' => 'prefixtestsuffix', 'payloadFileString' => '[prefixtestsuffix]'], true],
            [['value' => 'prefixtestsuffix', 'payloadFileString' => '[INVALID]'], true],
            [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[]'], true],
            [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[test]'], true],
            [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[prefixtestsuffix]'], true],
            [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[INVALID]'], true],
        ];
    }

    #[DataProvider('handleRegularPayloadDataProvider')]
    public function testHandleRegularPayload(array $input, bool $output): void
    {
        $this->markTestIncomplete();

        $this->abstractPayloadFilter->filterName = 'ABSTRACTPAYLOAD';
        define('CONFIG', ['FILTER_ABSTRACTPAYLOAD_PAYLOAD_FILES' => $input['payloadFileString']]);
        $method = self::getMethod($this->abstractPayloadFilter, 'handleRegularPayload');
        $this->assertEquals($output, $method->invokeArgs($this->abstractPayloadFilter, [$input['value']]));
    }

    public static function handleRegularPayloadDataProvider(): array
    {
        return [
            [['value' => 'test', 'payloadFileString' => '[]'], true],
            [['value' => 'test', 'payloadFileString' => '[test]'], true],
            [['value' => 'test', 'payloadFileString' => '[prefixtestsuffix]'], true],
            [['value' => 'test', 'payloadFileString' => '[INVALID]'], true],
            [['value' => ['test'], 'payloadFileString' => '[]'], true],
            [['value' => ['test'], 'payloadFileString' => '[test]'], true],
            [['value' => ['test'], 'payloadFileString' => '[prefixtestsuffix]'], true],
            [['value' => ['test'], 'payloadFileString' => '[INVALID]'], true],
            [['value' => 'prefixtestsuffix', 'payloadFileString' => '[]'], true],
            [['value' => 'prefixtestsuffix', 'payloadFileString' => '[test]'], true],
            [['value' => 'prefixtestsuffix', 'payloadFileString' => '[prefixtestsuffix]'], true],
            [['value' => 'prefixtestsuffix', 'payloadFileString' => '[INVALID]'], true],
            [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[]'], true],
            [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[test]'], true],
            [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[prefixtestsuffix]'], true],
            [['value' => ['prefixtestsuffix'], 'payloadFileString' => '[INVALID]'], true],
        ];
    }
}