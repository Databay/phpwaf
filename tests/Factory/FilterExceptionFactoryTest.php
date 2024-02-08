<?php

namespace App\Tests\Factory;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterCriticalException;
use App\Exception\FilterRejectException;
use App\Exception\FilterTimeoutException;
use App\Exception\FilterWarningException;
use App\Factory\FilterExceptionFactory;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class FilterExceptionFactoryTest extends BaseTestCase
{
    #[DataProvider('getExceptionDataProvider')]
    public function testGetException(string $input, string $output): void
    {
        $request = new Request(null, null, null, null, null, null, null, null);
        $filter = new class($input) extends AbstractFilter {
            private string $blockingType;

            public function __construct(string $blockingType)
            {
                parent::__construct();
                $this->blockingType = $blockingType;
            }

            public function getBlockingType(): string
            {
                return $this->blockingType;
            }

            public function apply(Request $request) {}
        };

        $this->assertInstanceOf($output, FilterExceptionFactory::getException($filter, $request));
    }

    public static function getExceptionDataProvider(): array
    {
        return [
            ['INVALID', FilterWarningException::class],
            ['WARNING', FilterWarningException::class],
            ['REJECT', FilterRejectException::class],
            ['TIMEOUT', FilterTimeoutException::class],
            ['CRITICAL', FilterCriticalException::class],
        ];
    }
}