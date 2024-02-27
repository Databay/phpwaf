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
        $payloadException = new class($input['blockingType'], $input['payloadFile'], $input['strictMatch']) extends PayloadException {};
        $this->assertEquals($output, $payloadException->getPayloadFile());
    }

    public static function getPayloadFileDataProvider(): array
    {
        return [
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test1', 'strictMatch' => true], 'Test1'],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test1', 'strictMatch' => false], 'Test1'],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test1', 'strictMatch' => true], 'Test1'],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test1', 'strictMatch' => false], 'Test1'],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test2', 'strictMatch' => false], 'Test2'],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test2', 'strictMatch' => true], 'Test2'],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test2', 'strictMatch' => false], 'Test2'],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test2', 'strictMatch' => true], 'Test2'],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test3', 'strictMatch' => true], 'Test3'],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test3', 'strictMatch' => false], 'Test3'],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test3', 'strictMatch' => true], 'Test3'],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test3', 'strictMatch' => false], 'Test3'],
        ];
    }

    #[DataProvider('isPayloadStrictMatchDataProvider')]
    public function testIsPayloadStrictMatch(array $input, bool $output): void
    {
        $payloadException = new class($input['blockingType'], $input['payloadFile'], $input['strictMatch']) extends PayloadException {};
        $this->assertEquals($output, $payloadException->isPayloadStrictMatch());
    }

    public static function isPayloadStrictMatchDataProvider(): array
    {
        return [
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test1', 'strictMatch' => true], true],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test1', 'strictMatch' => false], false],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test1', 'strictMatch' => true], true],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test1', 'strictMatch' => false], false],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test2', 'strictMatch' => false], false],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test2', 'strictMatch' => true], true],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test2', 'strictMatch' => false], false],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test2', 'strictMatch' => true], true],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test3', 'strictMatch' => true], true],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test3', 'strictMatch' => false], false],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test3', 'strictMatch' => true], true],
            [['blockingType' => 'WARNING', 'payloadFile' => 'Test3', 'strictMatch' => false], false],
        ];
    }
}