<?php

namespace App\Tests\Service {
    use App\Abstracts\AbstractFilter;
    use App\Entity\Request;
    use App\Filter\DomainFilter;
    use App\Filter\FILESFilter;
    use App\Filter\GETFilter;
    use App\Filter\HttpFilter;
    use App\Filter\POSTFilter;
    use App\Filter\RequestMethodFilter;
    use App\Filter\URIFilter;
    use App\Service\Logger;
    use App\Tests\BaseTestCase;
    use PHPUnit\Framework\Attributes\DataProvider;
    use function App\Service\date;

    class LoggerTest extends BaseTestCase
    {
        #[DataProvider('getLogEntryDataProvider')]
        public function testGetLogEntry(array $input, string $output): void
        {
            $this->assertEquals($output . PHP_EOL, self::getMethod(Logger::class, 'getLogEntry')->invoke(null, $input['type'], $input['request'], $input['filter']));
        }

        public static function getLogEntryDataProvider(): array
        {
            return [
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http / [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http /longer/uri/test [' . DomainFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new DomainFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http /longer/uri/test [' . DomainFilter::class . ']',
                ],

                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http / [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http /longer/uri/test [' . FILESFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new FILESFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http /longer/uri/test [' . FILESFilter::class . ']',
                ],

                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http / [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http /longer/uri/test [' . GETFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new GETFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http /longer/uri/test [' . GETFilter::class . ']',
                ],

                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http / [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http /longer/uri/test [' . HttpFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new HttpFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http /longer/uri/test [' . HttpFilter::class . ']',
                ],

                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http / [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http /longer/uri/test [' . POSTFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new POSTFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http /longer/uri/test [' . POSTFilter::class . ']',
                ],

                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http / [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new RequestMethodFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http /longer/uri/test [' . RequestMethodFilter::class . ']',
                ],

                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http / [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 https /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 https /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 https /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 https /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost https /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost https /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost https /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'https',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost https /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] 127.0.0.1 http /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] 127.0.0.1 http /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] 127.0.0.1 http /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] 127.0.0.1 http /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_WARNING,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_WARNING . '] localhost http /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_REJECT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_REJECT . '] localhost http /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_TIMEOUT,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_TIMEOUT . '] localhost http /longer/uri/test [' . URIFilter::class . ']',
                ],
                [
                    [
                        'type' => AbstractFilter::BLOCKING_TYPE_CRITICAL,
                        'request' => new Request(null, null, null, null, null, null, [
                            'REMOTE_ADDR' => 'localhost',
                            'REQUEST_METHOD' => 'http',
                            'REQUEST_URI' => '/longer/uri/test'
                        ]),
                        'filter' => new URIFilter()
                    ],
                    '[' . date() .'] [' . AbstractFilter::BLOCKING_TYPE_CRITICAL . '] localhost http /longer/uri/test [' . URIFilter::class . ']',
                ],
            ];
        }
    }
}