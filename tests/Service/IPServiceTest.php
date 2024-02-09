<?php

namespace App\Tests\Service;

use App\Entity\Request;
use App\Service\IPService;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class IPServiceTest extends BaseTestCase
{
    #[DataProvider('getIPBanDurationDataProvider')]
    public function testGetIPBanDuration(array $input, int $output): void
    {
        define('CONFIG', $input);
        $this->assertEquals($output, self::getMethod(IPService::class, 'getIPBanDuration')->invoke(null));
    }

    public static function getIPBanDurationDataProvider(): array
    {
        return [
            [['IP_BAN_DURATION' => 'permanent'], PHP_INT_MAX],
            [['IP_BAN_DURATION' => 'INVALID'], 1],
            [['IP_BAN_DURATION' => '2'], 2],
            [['IP_BAN_DURATION' => '1'], 1],
            [['IP_BAN_DURATION' => '0'], 1],
            [[], 60],
        ];
    }

    #[DataProvider('getIPDataProvider')]
    #[RunInSeparateProcess]
    public function testGetIP(array $input, string $output): void
    {
        define('CONFIG', ['IP_ADDRESS_KEY' => $input['IP_ADDRESS_KEY']]);
        $request = new Request(null, null, null, null, null, null, [$input['IP_ADDRESS_KEY'] => $input['IP']], null);
        $this->assertEquals($output, IPService::getIP($request));
    }

    public static function getIPDataProvider(): array
    {
        return [
            [
                [
                    'IP_ADDRESS_KEY' => 'REMOTE_ADDR',
                    'IP' => '127.0.0.1',
                ], '127.0.0.1',
            ],
            [
                [
                    'IP_ADDRESS_KEY' => 'HTTP_X_FORWARDED_FOR',
                    'IP' => '127.0.0.1',
                ], '127.0.0.1',
            ],
            [
                [
                    'IP_ADDRESS_KEY' => 'REMOTE_ADDR',
                    'IP' => '192.168.0.0',
                ], '192.168.0.0',
            ],
            [
                [
                    'IP_ADDRESS_KEY' => 'HTTP_X_FORWARDED_FOR',
                    'IP' => '192.168.0.0',
                ], '192.168.0.0',
            ],
            [
                [
                    'IP_ADDRESS_KEY' => 'REMOTE_ADDR',
                    'IP' => '10.0.0.0',
                ], '10.0.0.0',
            ],
            [
                [
                    'IP_ADDRESS_KEY' => 'HTTP_X_FORWARDED_FOR',
                    'IP' => '10.0.0.0',
                ], '10.0.0.0',
            ],
        ];
    }

    #[DataProvider('isValidIPDataProvider')]
    public function testIsValidIP(string $input, bool $output): void
    {
        $this->assertEquals($output, self::getMethod(IPService::class, 'isValidIP')->invoke(null, $input));
    }

    public static function isValidIPDataProvider(): array
    {
        return [
            ['INVALID', false],

            ['127.0.0.1', true],
            ['192.168.0.1', true],
            ['10.0.0.1', true],
            ['172.16.0.1', true],
            ['255.255.255.255', true],
            ['8.8.8.8', true],
            ['123.45.67.89', true],
            ['198.51.100.1', true],
            ['0.0.0.0', true],
            ['169.254.0.1', true],

            ['300.0.0.1', false],
            ['256.256.256.256', false],
            ['192.168.0', false],
            ['192.168.0.256', false],
            ['192.168.0.-1', false],
            ['192.168.0.1.2', false],
            ['192.168.0.1.1', false],
            ['192.168.0', false],
            ['192.168.0.300', false],
            ['500.700.900.1000', false],
        ];
    }
}