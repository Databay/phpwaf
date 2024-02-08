<?php

namespace App\Tests\Exception;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

class FilterExceptionTest extends BaseTestCase
{
    public function testGetFilter(): void
    {
        $filter = $this->createStub(AbstractFilter::class);
        $request = $this->createStub(Request::class);
        $filter1Exception = new class($filter, $request) extends FilterException {};
        $this->assertEquals($filter, $filter1Exception->getFilter());

    }

    public function testGetRequest(): void
    {
        $filter = $this->createStub(AbstractFilter::class);
        $request = $this->createStub(Request::class);
        $filter1Exception = new class($filter, $request) extends FilterException {};
        $this->assertEquals($request, $filter1Exception->getRequest());
    }

    #[RunInSeparateProcess]
    #[DataProvider('getLogEntryContentDataProvider')]
    public function testGetLogEntryContent(array $input, string $output): void
    {
        $filter = $this->createConfiguredMock(AbstractFilter::class, ['getLogEntryContent' => $input['filter']]);
        $request = $this->createMock(Request::class);
        $filter1Exception = new class($filter, $request, $input['message']) extends FilterException {};
        define('CONFIG', ['LOGGER_LOG_LEVEL' => $input['logLevel']]);
        $this->assertEquals($output, $filter1Exception->getLogEntryContent());
    }

    public static function getLogEntryContentDataProvider(): array
    {
        return [
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '-1',
                    'message' => 'Test1',
                ], 'FILTER1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '0',
                    'message' => 'Test1',
                ], 'FILTER1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '1',
                    'message' => 'Test1',
                ], 'FILTER1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '2',
                    'message' => 'Test1',
                ], 'FILTER1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '3',
                    'message' => 'Test1',
                ], 'FILTER1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '4',
                    'message' => 'Test1',
                ], 'FILTER1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '5',
                    'message' => 'Test1',
                ], 'FILTER1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '6',
                    'message' => 'Test1',
                ], 'FILTER1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '7',
                    'message' => 'Test1',
                ], 'FILTER1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '8',
                    'message' => 'Test1',
                ], 'FILTER1 => Test1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '9',
                    'message' => 'Test1',
                ], 'FILTER1 => Test1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '10',
                    'message' => 'Test1',
                ], 'FILTER1 => Test1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '11',
                    'message' => 'Test1',
                ], 'FILTER1 => Test1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '12',
                    'message' => 'Test1',
                ], 'FILTER1 => Test1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '13',
                    'message' => 'Test1',
                ], 'FILTER1 => Test1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '14',
                    'message' => 'Test1',
                ], 'FILTER1 => Test1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '15',
                    'message' => 'Test1',
                ], 'FILTER1 => Test1',
            ],
            [
                [
                    'filter' => 'FILTER1',
                    'logLevel' => '16',
                    'message' => 'Test1',
                ], 'FILTER1 => Test1',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '-1',
                    'message' => 'Test2',
                ], 'FILTER2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '0',
                    'message' => 'Test2',
                ], 'FILTER2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '1',
                    'message' => 'Test2',
                ], 'FILTER2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '2',
                    'message' => 'Test2',
                ], 'FILTER2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '3',
                    'message' => 'Test2',
                ], 'FILTER2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '4',
                    'message' => 'Test2',
                ], 'FILTER2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '5',
                    'message' => 'Test2',
                ], 'FILTER2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '6',
                    'message' => 'Test2',
                ], 'FILTER2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '7',
                    'message' => 'Test2',
                ], 'FILTER2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '8',
                    'message' => 'Test2',
                ], 'FILTER2 => Test2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '9',
                    'message' => 'Test2',
                ], 'FILTER2 => Test2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '10',
                    'message' => 'Test2',
                ], 'FILTER2 => Test2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '11',
                    'message' => 'Test2',
                ], 'FILTER2 => Test2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '12',
                    'message' => 'Test2',
                ], 'FILTER2 => Test2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '13',
                    'message' => 'Test2',
                ], 'FILTER2 => Test2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '14',
                    'message' => 'Test2',
                ], 'FILTER2 => Test2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '15',
                    'message' => 'Test2',
                ], 'FILTER2 => Test2',
            ],
            [
                [
                    'filter' => 'FILTER2',
                    'logLevel' => '16',
                    'message' => 'Test2',
                ], 'FILTER2 => Test2',
            ],
        ];
    }
}