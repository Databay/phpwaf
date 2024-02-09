<?php

namespace App\Tests\Exception;

use App\Exception\PayloadException;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PayloadExceptionTest extends BaseTestCase
{
    #[DataProvider('getPayloadFileDataProvider')]
    public function testGetPayloadFile(array $input, string $output): void
    {
        $payloadException = new class($input['payloadFile'], $input['strictMatch'], $input['criticalPayload']) extends PayloadException {};
        $this->assertEquals($output, $payloadException->getPayloadFile());
    }

    public static function getPayloadFileDataProvider(): array
    {
        return [
            [['payloadFile' => 'Test1', 'strictMatch' => true, 'criticalPayload' => true], 'Test1'],
            [['payloadFile' => 'Test1', 'strictMatch' => false, 'criticalPayload' => true], 'Test1'],
            [['payloadFile' => 'Test1', 'strictMatch' => true, 'criticalPayload' => false], 'Test1'],
            [['payloadFile' => 'Test1', 'strictMatch' => false, 'criticalPayload' => false], 'Test1'],

            [['payloadFile' => 'Test2', 'strictMatch' => false, 'criticalPayload' => false], 'Test2'],
            [['payloadFile' => 'Test2', 'strictMatch' => true, 'criticalPayload' => false], 'Test2'],
            [['payloadFile' => 'Test2', 'strictMatch' => false, 'criticalPayload' => true], 'Test2'],
            [['payloadFile' => 'Test2', 'strictMatch' => true, 'criticalPayload' => true], 'Test2'],

            [['payloadFile' => 'Test3', 'strictMatch' => true, 'criticalPayload' => true], 'Test3'],
            [['payloadFile' => 'Test3', 'strictMatch' => false, 'criticalPayload' => true], 'Test3'],
            [['payloadFile' => 'Test3', 'strictMatch' => true, 'criticalPayload' => false], 'Test3'],
            [['payloadFile' => 'Test3', 'strictMatch' => false, 'criticalPayload' => false], 'Test3'],
        ];
    }

    #[DataProvider('isPayloadStrictMatchDataProvider')]
    public function testIsPayloadStrictMatch(array $input, bool $output): void
    {
        $payloadException = new class($input['payloadFile'], $input['strictMatch'], $input['criticalPayload']) extends PayloadException {};
        $this->assertEquals($output, $payloadException->isPayloadStrictMatch());
    }

    public static function isPayloadStrictMatchDataProvider(): array
    {
        return [
            [['payloadFile' => 'Test1', 'strictMatch' => true, 'criticalPayload' => true], true],
            [['payloadFile' => 'Test1', 'strictMatch' => false, 'criticalPayload' => true], false],
            [['payloadFile' => 'Test1', 'strictMatch' => true, 'criticalPayload' => false], true],
            [['payloadFile' => 'Test1', 'strictMatch' => false, 'criticalPayload' => false], false],

            [['payloadFile' => 'Test2', 'strictMatch' => false, 'criticalPayload' => false], false],
            [['payloadFile' => 'Test2', 'strictMatch' => true, 'criticalPayload' => false], true],
            [['payloadFile' => 'Test2', 'strictMatch' => false, 'criticalPayload' => true], false],
            [['payloadFile' => 'Test2', 'strictMatch' => true, 'criticalPayload' => true], true],

            [['payloadFile' => 'Test3', 'strictMatch' => true, 'criticalPayload' => true], true],
            [['payloadFile' => 'Test3', 'strictMatch' => false, 'criticalPayload' => true], false],
            [['payloadFile' => 'Test3', 'strictMatch' => true, 'criticalPayload' => false], true],
            [['payloadFile' => 'Test3', 'strictMatch' => false, 'criticalPayload' => false], false],
        ];
    }

    #[DataProvider('isCriticalPayloadDataProvider')]
    public function testIsCriticalPayload(array $input, bool $output): void
    {
        $payloadException = new class($input['payloadFile'], $input['strictMatch'], $input['criticalPayload']) extends PayloadException {};
        $this->assertEquals($output, $payloadException->isCriticalPayload());
    }

    public static function isCriticalPayloadDataProvider(): array
    {
        return [
            [['payloadFile' => 'Test1', 'strictMatch' => true, 'criticalPayload' => true], true],
            [['payloadFile' => 'Test1', 'strictMatch' => false, 'criticalPayload' => true], true],
            [['payloadFile' => 'Test1', 'strictMatch' => true, 'criticalPayload' => false], false],
            [['payloadFile' => 'Test1', 'strictMatch' => false, 'criticalPayload' => false], false],

            [['payloadFile' => 'Test2', 'strictMatch' => false, 'criticalPayload' => false], false],
            [['payloadFile' => 'Test2', 'strictMatch' => true, 'criticalPayload' => false], false],
            [['payloadFile' => 'Test2', 'strictMatch' => false, 'criticalPayload' => true], true],
            [['payloadFile' => 'Test2', 'strictMatch' => true, 'criticalPayload' => true], true],

            [['payloadFile' => 'Test3', 'strictMatch' => true, 'criticalPayload' => true], true],
            [['payloadFile' => 'Test3', 'strictMatch' => false, 'criticalPayload' => true], true],
            [['payloadFile' => 'Test3', 'strictMatch' => true, 'criticalPayload' => false], false],
            [['payloadFile' => 'Test3', 'strictMatch' => false, 'criticalPayload' => false], false],
        ];
    }
}