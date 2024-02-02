<?php

namespace App\Tests\Service;

use App\Entity\Request;
use App\Service\UserAgentService;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class UserAgentServiceTest extends BaseTestCase
{
    #[DataProvider('getUserAgentBanDurationDataProvider')]
    public function testGetUserAgentBanDuration(array $input, int $output): void
    {
        define('CONFIG', $input);
        $this->assertEquals($output, self::getMethod(UserAgentService::class, 'getUserAgentBanDuration')->invoke(null));
    }

    public static function getUserAgentBanDurationDataProvider(): array
    {
        return [
            [['USERAGENT_BAN_DURATION' => 'permanent'], PHP_INT_MAX],
            [['USERAGENT_BAN_DURATION' => 'INVALID'], 1],
            [['USERAGENT_BAN_DURATION' => '2'], 2],
            [['USERAGENT_BAN_DURATION' => '1'], 1],
            [['USERAGENT_BAN_DURATION' => '0'], 1],
        ];
    }

    #[DataProvider('getClientIdentifierDataProvider')]
    public function testGetClientIdentifier(array $input, string $output): void
    {
        define('CONFIG', ['USERAGENT_BAN_DURATION' => 'permanent']);
        $request = new Request(null, null, null, null, null, null, $input, null);
        $this->assertEquals($output, self::getMethod(UserAgentService::class, 'getClientIdentifier')->invokeArgs(null, [$request]));
    }

    public static function getClientIdentifierDataProvider(): array
    {
        return [
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test1',
                'HTTP_ACCEPT_LANGUAGE' => 'Test1',
                'HTTP_USER_AGENT' => 'Test1',
            ], 'cdaf527548d73008958b5953c4dc1a30ea3d19e2'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test1',
                'HTTP_ACCEPT_LANGUAGE' => 'Test1',
                'HTTP_USER_AGENT' => 'Test2',
            ], '780806b09a19a536f1441e5f5e795e21d6da04ce'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test1',
                'HTTP_ACCEPT_LANGUAGE' => 'Test1',
                'HTTP_USER_AGENT' => 'Test3',
            ], 'f1f44240296cef0f3b46c5247b7c9781d14dfad0'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test1',
                'HTTP_ACCEPT_LANGUAGE' => 'Test2',
                'HTTP_USER_AGENT' => 'Test1',
            ], 'cbb516198a800ad2e99df9d3e6a88c66c69dc13a'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test1',
                'HTTP_ACCEPT_LANGUAGE' => 'Test2',
                'HTTP_USER_AGENT' => 'Test2',
            ], 'd00213a41e8072980680db03f02c2dd945a4506e'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test1',
                'HTTP_ACCEPT_LANGUAGE' => 'Test2',
                'HTTP_USER_AGENT' => 'Test3',
            ], 'bb823d419dde936c8652a8631587beffea354a19'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test1',
                'HTTP_ACCEPT_LANGUAGE' => 'Test3',
                'HTTP_USER_AGENT' => 'Test1',
            ], '1e4b05e5b40838e7944178481c5de1269c02b405'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test1',
                'HTTP_ACCEPT_LANGUAGE' => 'Test3',
                'HTTP_USER_AGENT' => 'Test2',
            ], 'cd724722d9aceeac5fa68516cd53efde0987a3b4'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test1',
                'HTTP_ACCEPT_LANGUAGE' => 'Test3',
                'HTTP_USER_AGENT' => 'Test3',
            ], '0e25c38ddbacefc784e34717ff60151119c9b15c'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test2',
                'HTTP_ACCEPT_LANGUAGE' => 'Test1',
                'HTTP_USER_AGENT' => 'Test1',
            ], '4f7b169ebca1e032783987ff6dd78e5b61e155b6'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test2',
                'HTTP_ACCEPT_LANGUAGE' => 'Test1',
                'HTTP_USER_AGENT' => 'Test2',
            ], '33648224f596fa77bbaa0ec1340ac4d6517003a4'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test2',
                'HTTP_ACCEPT_LANGUAGE' => 'Test1',
                'HTTP_USER_AGENT' => 'Test3',
            ], 'e24ffd48d31c93a312c301c8560e7d23ec41dc42'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test2',
                'HTTP_ACCEPT_LANGUAGE' => 'Test2',
                'HTTP_USER_AGENT' => 'Test1',
            ], 'd856c7dd7dc8d6d690afb8466caabd8d50831f46'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test2',
                'HTTP_ACCEPT_LANGUAGE' => 'Test2',
                'HTTP_USER_AGENT' => 'Test2',
            ], '66bcef666cdcc69bc798c3d8d074982c6a136a20'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test2',
                'HTTP_ACCEPT_LANGUAGE' => 'Test2',
                'HTTP_USER_AGENT' => 'Test3',
            ], 'fb65f55cc4ac6c60ae810456bd37798e72a73690'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test2',
                'HTTP_ACCEPT_LANGUAGE' => 'Test3',
                'HTTP_USER_AGENT' => 'Test1',
            ], '6e4bda25025f9686da620bd18d7ff5de576593fd'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test2',
                'HTTP_ACCEPT_LANGUAGE' => 'Test3',
                'HTTP_USER_AGENT' => 'Test2',
            ], '2538b36c028259536c9804d555acf1086d02eb2d'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test2',
                'HTTP_ACCEPT_LANGUAGE' => 'Test3',
                'HTTP_USER_AGENT' => 'Test3',
            ], '2d07a5b6c94c81da1446e6102d4fbfb57a9273ad'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test3',
                'HTTP_ACCEPT_LANGUAGE' => 'Test1',
                'HTTP_USER_AGENT' => 'Test1',
            ], '5e0727d07a2c9c428ea308d47774fc83eb82d631'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test3',
                'HTTP_ACCEPT_LANGUAGE' => 'Test1',
                'HTTP_USER_AGENT' => 'Test2',
            ], 'a68a58ecb48febe058a6c36fa73182a399e0572c'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test3',
                'HTTP_ACCEPT_LANGUAGE' => 'Test1',
                'HTTP_USER_AGENT' => 'Test3',
            ], 'ca424474bf7b4ef8ab261fc27332c9b87421b5c4'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test3',
                'HTTP_ACCEPT_LANGUAGE' => 'Test2',
                'HTTP_USER_AGENT' => 'Test1',
            ], 'a6ce193d7dfe48af3106e3833b5f529445d58445'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test3',
                'HTTP_ACCEPT_LANGUAGE' => 'Test2',
                'HTTP_USER_AGENT' => 'Test2',
            ], '5cab5a2ac35684b1ba3e600e65f2e8b0798458bb'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test3',
                'HTTP_ACCEPT_LANGUAGE' => 'Test2',
                'HTTP_USER_AGENT' => 'Test3',
            ], 'cdb0f3700238cb34b4fb9238e25248006257816c'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test3',
                'HTTP_ACCEPT_LANGUAGE' => 'Test3',
                'HTTP_USER_AGENT' => 'Test1',
            ], 'e77ab79f52f8fab06a8b6206d443922dc2058e5a'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test3',
                'HTTP_ACCEPT_LANGUAGE' => 'Test3',
                'HTTP_USER_AGENT' => 'Test2',
            ], '01b737eaf903b772bc3bbc82e8baa5f96939be9e'],
            ['SERVER' => [
                'REMOTE_ADDR' => 'Test3',
                'HTTP_ACCEPT_LANGUAGE' => 'Test3',
                'HTTP_USER_AGENT' => 'Test3',
            ], '59c2894e9c746f579f528198c0acfeedf7e8d779'],
        ];
    }
}