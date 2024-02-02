<?php

namespace App\Tests\Service;

use App\Service\IPService;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
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
}