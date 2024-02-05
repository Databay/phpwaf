<?php

namespace App\Tests\Service {
    use App\Service\Logger;
    use App\Tests\BaseTestCase;
    use PHPUnit\Framework\Attributes\DataProvider;

    class LoggerTest extends BaseTestCase
    {
        #[DataProvider('convertLogLevelStringToIntDataProvider')]
        public function testConvertLogLevelStringToInt(string $input, int $output): void
        {
            $this->assertEquals($output, self::getMethod(Logger::class, 'convertLogLevelStringToInt')->invokeArgs(null, [$input]));
        }

        public static function convertLogLevelStringToIntDataProvider(): array
        {
            return [
                ['INVALID', 15],
                ['DEBUG', 8],
                ['INFO', 4],
                ['WARNING', 2],
                ['CRITICAL', 1],
                ['NONE', 0],
                ['debug', 8],
                ['info', 4],
                ['warning', 2],
                ['critical', 1],
                ['none', 0],
                ['0', 0],
                ['1', 1],
                ['2', 2],
                ['3', 3],
                ['4', 4],
                ['5', 5],
                ['6', 6],
                ['7', 7],
                ['8', 8],
                ['9', 9],
                ['10', 10],
                ['11', 11],
                ['12', 12],
                ['13', 13],
                ['14', 14],
                ['15', 15],
                ['-1', 0],
                ['16', 15],
            ];
        }

        #[DataProvider('getLogEntryDataProvider')]
        public function testGetLogEntry(array $input, string $output): void
        {
            $this->assertEquals($output, self::getMethod(Logger::class, 'getLogEntry')->invokeArgs(null, [$input['content'], $input['logLevel']]));
        }

        public static function getLogEntryDataProvider()
        {
            return [
                [['content' => 'test', 'logLevel' => 'DEBUG'], '[1970-01-01 00:00:00] [DEBUG] test' . PHP_EOL],
                [['content' => 'test', 'logLevel' => 'INFO'], '[1970-01-01 00:00:00] [INFO] test' . PHP_EOL],
                [['content' => 'test', 'logLevel' => 'WARNING'], '[1970-01-01 00:00:00] [WARNING] test' . PHP_EOL],
                [['content' => 'test', 'logLevel' => 'CRITICAL'], '[1970-01-01 00:00:00] [CRITICAL] test' . PHP_EOL],
                [['content' => 'test', 'logLevel' => 'NONE'], '[1970-01-01 00:00:00] [NONE] test' . PHP_EOL],
                [['content' => 'A longer test', 'logLevel' => 'DEBUG'], '[1970-01-01 00:00:00] [DEBUG] A longer test' . PHP_EOL],
                [['content' => 'A longer test', 'logLevel' => 'INFO'], '[1970-01-01 00:00:00] [INFO] A longer test' . PHP_EOL],
                [['content' => 'A longer test', 'logLevel' => 'WARNING'], '[1970-01-01 00:00:00] [WARNING] A longer test' . PHP_EOL],
                [['content' => 'A longer test', 'logLevel' => 'CRITICAL'], '[1970-01-01 00:00:00] [CRITICAL] A longer test' . PHP_EOL],
                [['content' => 'A longer test', 'logLevel' => 'NONE'], '[1970-01-01 00:00:00] [NONE] A longer test' . PHP_EOL],
            ];
        }
    }
}