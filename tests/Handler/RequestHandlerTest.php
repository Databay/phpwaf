<?php

namespace App\Tests\Handler;

use App\Abstracts\AbstractFilter;
use App\Filter\DomainFilter;
use App\Filter\FILESFilter;
use App\Filter\GETFilter;
use App\Filter\HttpFilter;
use App\Filter\POSTFilter;
use App\Filter\RequestMethodFilter;
use App\Filter\URIFilter;
use App\Handler\RequestHandler;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class RequestHandlerTest extends BaseTestCase
{
    #[DataProvider('getAllFiltersDataProvider')]
    public function testGetAllFilters(AbstractFilter|string $output): void
    {
        $class = new \ReflectionClass(new RequestHandler());
        $method = $class->getMethod('getAllFilters');
        $method->setAccessible(true);

        $this->assertArrayContainsInstanceOf($output, $method->invoke($class));
    }

    public static function getAllFiltersDataProvider(): array
    {
        return [
            [DomainFilter::class],
            [FILESFilter::class],
            [GETFilter::class],
            [HttpFilter::class],
            [POSTFilter::class],
            [RequestMethodFilter::class],
            [URIFilter::class],
        ];
    }
}