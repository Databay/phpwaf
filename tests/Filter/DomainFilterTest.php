<?php

namespace App\Tests\Filter;

use App\Entity\Request;
use App\Filter\DomainFilter;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class DomainFilterTest extends BaseTestCase
{
    #[DataProvider('applyDataProvider')]
    public function testApply(array $input, bool $output): void
    {
        define('CONFIG', [
            'FILTER_DOMAIN_ACTIVE' => $input['FILTER_DOMAIN_ACTIVE'],
            'FILTER_DOMAIN_ALLOWED_DOMAINS' => $input['FILTER_DOMAIN_ALLOWED_DOMAINS'],
        ]);

        $request = new Request(null, null, null, null, null, null, $input['server']);
        $this->assertEquals($output, (new DomainFilter())->apply($request));
    }

    public static function applyDataProvider(): array
    {
        return [
            [['FILTER_DOMAIN_ACTIVE' => 'true', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[localhost]', 'server' => ['HTTP_HOST' => 'localhost']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'true', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[localhost]', 'server' => ['HTTP_HOST' => '127.0.0.1']], false],
            [['FILTER_DOMAIN_ACTIVE' => 'true', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[localhost]', 'server' => ['HTTP_HOST' => '127.0.0.1:443']], false],
            [['FILTER_DOMAIN_ACTIVE' => 'true', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[localhost]', 'server' => ['HTTP_HOST' => 'localhost:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'true', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[127.0.0.1]', 'server' => ['HTTP_HOST' => 'localhost']], false],
            [['FILTER_DOMAIN_ACTIVE' => 'true', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[127.0.0.1]', 'server' => ['HTTP_HOST' => 'localhost:443']], false],
            [['FILTER_DOMAIN_ACTIVE' => 'true', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[127.0.0.1]', 'server' => ['HTTP_HOST' => '127.0.0.1']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'true', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[127.0.0.1]', 'server' => ['HTTP_HOST' => '127.0.0.1:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'true', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => 'INVALID', 'server' => ['HTTP_HOST' => 'localhost']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'true', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => 'INVALID', 'server' => ['HTTP_HOST' => 'localhost:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'true', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => 'INVALID', 'server' => ['HTTP_HOST' => '127.0.0.1']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'true', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => 'INVALID', 'server' => ['HTTP_HOST' => '127.0.0.1:443']], true],

            [['FILTER_DOMAIN_ACTIVE' => 'false', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[localhost]', 'server' => ['HTTP_HOST' => 'localhost']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'false', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[localhost]', 'server' => ['HTTP_HOST' => 'localhost:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'false', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[localhost]', 'server' => ['HTTP_HOST' => '127.0.0.1']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'false', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[localhost]', 'server' => ['HTTP_HOST' => '127.0.0.1:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'false', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[127.0.0.1]', 'server' => ['HTTP_HOST' => 'localhost']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'false', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[127.0.0.1]', 'server' => ['HTTP_HOST' => 'localhost:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'false', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[127.0.0.1]', 'server' => ['HTTP_HOST' => '127.0.0.1']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'false', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[127.0.0.1]', 'server' => ['HTTP_HOST' => '127.0.0.1:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'false', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => 'INVALID', 'server' => ['HTTP_HOST' => 'localhost']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'false', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => 'INVALID', 'server' => ['HTTP_HOST' => 'localhost:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'false', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => 'INVALID', 'server' => ['HTTP_HOST' => '127.0.0.1']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'false', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => 'INVALID', 'server' => ['HTTP_HOST' => '127.0.0.1:443']], true],

            [['FILTER_DOMAIN_ACTIVE' => 'INVALID', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[localhost]', 'server' => ['HTTP_HOST' => 'localhost']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'INVALID', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[localhost]', 'server' => ['HTTP_HOST' => 'localhost:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'INVALID', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[localhost]', 'server' => ['HTTP_HOST' => '127.0.0.1']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'INVALID', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[localhost]', 'server' => ['HTTP_HOST' => '127.0.0.1:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'INVALID', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[127.0.0.1]', 'server' => ['HTTP_HOST' => 'localhost']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'INVALID', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[127.0.0.1]', 'server' => ['HTTP_HOST' => 'localhost:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'INVALID', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[127.0.0.1]', 'server' => ['HTTP_HOST' => '127.0.0.1']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'INVALID', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => '[127.0.0.1]', 'server' => ['HTTP_HOST' => '127.0.0.1:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'INVALID', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => 'INVALID', 'server' => ['HTTP_HOST' => 'localhost']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'INVALID', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => 'INVALID', 'server' => ['HTTP_HOST' => 'localhost:443']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'INVALID', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => 'INVALID', 'server' => ['HTTP_HOST' => '127.0.0.1']], true],
            [['FILTER_DOMAIN_ACTIVE' => 'INVALID', 'FILTER_DOMAIN_ALLOWED_DOMAINS' => 'INVALID', 'server' => ['HTTP_HOST' => '127.0.0.1:443']], true],
        ];
    }
}