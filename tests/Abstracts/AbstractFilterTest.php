<?php

namespace App\Tests\Abstracts;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class AbstractFilterTest extends BaseTestCase
{
    private AbstractFilter $abstractFilter;

    public function setUp(): void
    {
        parent::setUp();

        $this->abstractFilter = new class extends AbstractFilter {
            public $filterName;

            public $criticalMatch;

            public function apply(Request $request): bool
            {
                return true;
            }
        };
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(AbstractFilter::class, $this->abstractFilter);
        $this->assertTrue($this->abstractFilter->apply($this->createMock(Request::class)));
    }

    #[DataProvider('getBlockingTypeDataProvider')]
    public function testGetBlockingType(array $input, string $output): void
    {
        if (!is_bool($input['criticalMatch'])) {
            $this->fail('criticalMatch must be a boolean');
        }

        if ($input['criticalMatch'] === true) {
            $this->abstractFilter->criticalMatch = true;
            define('CONFIG', ['FILTER_ABSTRACT_CRITICAL_BLOCKING_TYPE' => $input['blockingType']]);
        } else {
            define('CONFIG', ['FILTER_ABSTRACT_BLOCKING_TYPE' => $input['blockingType']]);
        }

        $this->abstractFilter->filterName = 'ABSTRACT';
        $this->assertEquals($output, $this->abstractFilter->getBlockingType());
    }

    public static function getBlockingTypeDataProvider(): array
    {
        return [
            [['criticalMatch' => true, 'blockingType' => 'invalidType'], 'CRITICAL'],
            [['criticalMatch' => false, 'blockingType' => 'invalidType'], 'WARNING'],
            [['criticalMatch' => true, 'blockingType' => 'WARNING'], 'WARNING'],
            [['criticalMatch' => false, 'blockingType' => 'WARNING'], 'WARNING'],
            [['criticalMatch' => true, 'blockingType' => 'REJECT'], 'REJECT'],
            [['criticalMatch' => false, 'blockingType' => 'REJECT'], 'REJECT'],
            [['criticalMatch' => true, 'blockingType' => 'TIMEOUT'], 'TIMEOUT'],
            [['criticalMatch' => false, 'blockingType' => 'TIMEOUT'], 'TIMEOUT'],
            [['criticalMatch' => true, 'blockingType' => 'CRITICAL'], 'CRITICAL'],
            [['criticalMatch' => false, 'blockingType' => 'CRITICAL'], 'CRITICAL'],
        ];
    }

    #[DataProvider('isFilterActiveDataProvider')]
    public function testIsFilterActive(string $input, bool $output): void
    {
        $this->abstractFilter->filterName = 'ABSTRACT';
        define('CONFIG', ['FILTER_ABSTRACT_ACTIVE' => $input]);
        $method = self::getMethod(AbstractFilter::class, 'isFilterActive');
        $this->assertEquals($output, $method->invoke($this->abstractFilter));
    }

    public static function isFilterActiveDataProvider(): array
    {
        return [
            ['true', true],
            ['false', false],
            ['invalid', false],
        ];
    }

    #[DataProvider('isStringValidListDataProvider')]
    public function testIsStringValidList(string $input, bool $output): void
    {
        $method = self::getMethod(AbstractFilter::class, 'isStringValidList');
        $this->assertEquals($output, $method->invoke($this->abstractFilter, $input));
    }

    public static function isStringValidListDataProvider(): array
    {
        return [
            ['INVALID', false],
            ['', false],
            ['[]', true],
            ['[ ]', true],
            ['[VALID]', true],
            ['[ VALID]', true],
            ['[VALID ]', true],
            ['[VALID,VALID]', true],
        ];
    }

    #[DataProvider('getLogEntryContentDataProvider')]
    #[RunInSeparateProcess]
    public function testGetLogEntryContent(array $input, string $output): void
    {
        define('CONFIG', ['IP_ADDRESS_KEY' => $input['IP_ADDRESS_KEY']]);
        $this->abstractFilter->filterName = 'ABSTRACT';
        $this->assertEquals($output, $this->abstractFilter->getLogEntryContent($input['request']));
    }

    public static function getLogEntryContentDataProvider(): array
    {
        return [
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/', 'REMOTE_ADDR' => '127.0.0.1'], null),
                    'IP_ADDRESS_KEY' => 'REMOTE_ADDR'
                ], '127.0.0.1 GET / [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/', 'REMOTE_ADDR' => 'localhost'], null),
                    'IP_ADDRESS_KEY' => 'REMOTE_ADDR'
                ], 'localhost GET / [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/test', 'REMOTE_ADDR' => '127.0.0.1'], null),
                    'IP_ADDRESS_KEY' => 'REMOTE_ADDR'
                ], '127.0.0.1 GET /test [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/test', 'REMOTE_ADDR' => 'localhost'], null),
                    'IP_ADDRESS_KEY' => 'REMOTE_ADDR'
                ], 'localhost GET /test [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/', 'REMOTE_ADDR' => '127.0.0.1'], null),
                    'IP_ADDRESS_KEY' => 'REMOTE_ADDR'
                ], '127.0.0.1 POST / [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/', 'REMOTE_ADDR' => 'localhost'], null),
                    'IP_ADDRESS_KEY' => 'REMOTE_ADDR'
                ], 'localhost POST / [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/test', 'REMOTE_ADDR' => '127.0.0.1'], null),
                    'IP_ADDRESS_KEY' => 'REMOTE_ADDR'
                ], '127.0.0.1 POST /test [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/test', 'REMOTE_ADDR' => 'localhost'], null),
                    'IP_ADDRESS_KEY' => 'REMOTE_ADDR'
                ], 'localhost POST /test [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/', 'HTTP_X_FORWARDED_FOR' => '127.0.0.1'], null),
                    'IP_ADDRESS_KEY' => 'HTTP_X_FORWARDED_FOR'
                ], '127.0.0.1 GET / [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/', 'HTTP_X_FORWARDED_FOR' => 'localhost'], null),
                    'IP_ADDRESS_KEY' => 'HTTP_X_FORWARDED_FOR'
                ], 'localhost GET / [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/test', 'HTTP_X_FORWARDED_FOR' => '127.0.0.1'], null),
                    'IP_ADDRESS_KEY' => 'HTTP_X_FORWARDED_FOR'
                ], '127.0.0.1 GET /test [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/test', 'HTTP_X_FORWARDED_FOR' => 'localhost'], null),
                    'IP_ADDRESS_KEY' => 'HTTP_X_FORWARDED_FOR'
                ], 'localhost GET /test [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/', 'HTTP_X_FORWARDED_FOR' => '127.0.0.1'], null),
                    'IP_ADDRESS_KEY' => 'HTTP_X_FORWARDED_FOR'
                ], '127.0.0.1 POST / [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/', 'HTTP_X_FORWARDED_FOR' => 'localhost'], null),
                    'IP_ADDRESS_KEY' => 'HTTP_X_FORWARDED_FOR'
                ], 'localhost POST / [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/test', 'HTTP_X_FORWARDED_FOR' => '127.0.0.1'], null),
                    'IP_ADDRESS_KEY' => 'HTTP_X_FORWARDED_FOR'
                ], '127.0.0.1 POST /test [ABSTRACT]',
            ],
            [
                [
                    'request' => new Request(null, null, null, null, null, null, ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/test', 'HTTP_X_FORWARDED_FOR' => 'localhost'], null),
                    'IP_ADDRESS_KEY' => 'HTTP_X_FORWARDED_FOR'
                ], 'localhost POST /test [ABSTRACT]',
            ],
        ];
    }
}