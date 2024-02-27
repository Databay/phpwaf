<?php

namespace App\Tests\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Filter\FILESFilter;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class FILESFilterTest extends BaseTestCase
{
    #[DataProvider('applyDataProvider')]
    public function testApply(array $input, bool $output): void
    {
        define('CONFIG', [
            'FILTER_FILES_ACTIVE' => $input['FILTER_FILES_ACTIVE'] ?? 'true',
            'FILTER_FILES_MAX_COUNT' => $input['FILTER_FILES_MAX_COUNT'] ?? 'null',
            'FILTER_FILES_MAX_SIZE' => $input['FILTER_FILES_MAX_SIZE'] ?? 'null',
            'FILTER_FILES_ALLOWED_EXTENSIONS' => $input['FILTER_FILES_ALLOWED_EXTENSIONS'] ?? 'null',
            'FILTER_FILES_BLOCKING_TYPE' => AbstractFilter::BLOCKING_TYPE_WARNING,
        ]);

        $request = new Request(null, null, null, $input['FILES'], null, null, null, null);

        if ($output === false) {
            $this->expectException(FilterException::class);
        }

        $this->assertNull((new FILESFilter())->apply($request));
    }

    public static function applyDataProvider(): array
    {
        return [
            [['FILTER_FILES_ACTIVE' => 'false', 'FILES' => []], true],
            [['FILTER_FILES_ACTIVE' => 'INVALID', 'FILES' => []], true],

            [['FILTER_FILES_MAX_COUNT' => 'null', 'FILES' => []], true],

            [['FILTER_FILES_MAX_COUNT' => -1, 'FILES' => []], true],
            [['FILTER_FILES_MAX_COUNT' => -1, 'FILES' => [1]], false],
            [['FILTER_FILES_MAX_COUNT' => -1, 'FILES' => [1, 2]], false],

            [['FILTER_FILES_MAX_COUNT' => 0, 'FILES' => []], true],
            [['FILTER_FILES_MAX_COUNT' => 0, 'FILES' => [1]], false],
            [['FILTER_FILES_MAX_COUNT' => 0, 'FILES' => [1, 2]], false],

            [['FILTER_FILES_MAX_COUNT' => 1, 'FILES' => []], true],
            [['FILTER_FILES_MAX_COUNT' => 1, 'FILES' => [1]], true],
            [['FILTER_FILES_MAX_COUNT' => 1, 'FILES' => [1, 2]], false],

            [['FILTER_FILES_MAX_SIZE' => 'null', 'FILES' => []], true],

            [['FILTER_FILES_MAX_SIZE' => -1, 'FILES' => []], true],
            [['FILTER_FILES_MAX_SIZE' => -1, 'FILES' => [['size' => 0]]], true],
            [['FILTER_FILES_MAX_SIZE' => -1, 'FILES' => [['size' => 0], ['size' => 0]]], true],

            [['FILTER_FILES_MAX_SIZE' => 0, 'FILES' => []], true],
            [['FILTER_FILES_MAX_SIZE' => 0, 'FILES' => [['size' => 0]]], true],
            [['FILTER_FILES_MAX_SIZE' => 0, 'FILES' => [['size' => 0], ['size' => 0]]], true],

            [['FILTER_FILES_MAX_SIZE' => 1, 'FILES' => []], true],
            [['FILTER_FILES_MAX_SIZE' => 1, 'FILES' => [['size' => 0]]], true],
            [['FILTER_FILES_MAX_SIZE' => 1, 'FILES' => [['size' => 0], ['size' => 0]]], true],

            [['FILTER_FILES_MAX_SIZE' => -1, 'FILES' => []], true],
            [['FILTER_FILES_MAX_SIZE' => -1, 'FILES' => [['size' => 1]]], true],
            [['FILTER_FILES_MAX_SIZE' => -1, 'FILES' => [['size' => 1], ['size' => 1]]], true],

            [['FILTER_FILES_MAX_SIZE' => 0, 'FILES' => []], true],
            [['FILTER_FILES_MAX_SIZE' => 0, 'FILES' => [['size' => 1]]], true],
            [['FILTER_FILES_MAX_SIZE' => 0, 'FILES' => [['size' => 1], ['size' => 1]]], true],

            [['FILTER_FILES_MAX_SIZE' => 1, 'FILES' => []], true],
            [['FILTER_FILES_MAX_SIZE' => 1, 'FILES' => [['size' => 1]]], true],
            [['FILTER_FILES_MAX_SIZE' => 1, 'FILES' => [['size' => 1], ['size' => 1]]], true],

            [['FILTER_FILES_MAX_SIZE' => -1, 'FILES' => []], true],
            [['FILTER_FILES_MAX_SIZE' => -1, 'FILES' => [['size' => 2]]], false],
            [['FILTER_FILES_MAX_SIZE' => -1, 'FILES' => [['size' => 2], ['size' => 2]]], false],

            [['FILTER_FILES_MAX_SIZE' => 0, 'FILES' => []], true],
            [['FILTER_FILES_MAX_SIZE' => 0, 'FILES' => [['size' => 2]]], false],
            [['FILTER_FILES_MAX_SIZE' => 0, 'FILES' => [['size' => 2], ['size' => 2]]], false],

            [['FILTER_FILES_MAX_SIZE' => 1, 'FILES' => []], true],
            [['FILTER_FILES_MAX_SIZE' => 1, 'FILES' => [['size' => 2]]], false],
            [['FILTER_FILES_MAX_SIZE' => 1, 'FILES' => [['size' => 2], ['size' => 2]]], false],

            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '*', 'FILES' => []], true],
            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[]', 'FILES' => []], true],

            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[php]', 'FILES' => []], true],
            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[php]', 'FILES' => [['name' => '.txt']]], false],
            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[php]', 'FILES' => [['name' => '.txt'], ['name' => '.txt']]], false],

            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[txt]', 'FILES' => []], true],
            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[txt]', 'FILES' => [['name' => '.txt']]], true],
            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[txt]', 'FILES' => [['name' => '.txt'], ['name' => '.txt']]], true],

            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[php]', 'FILES' => []], true],
            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[php]', 'FILES' => [['name' => '.php']]], true],
            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[php]', 'FILES' => [['name' => '.php'], ['name' => '.php']]], true],

            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[txt]', 'FILES' => []], true],
            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[txt]', 'FILES' => [['name' => 'txt']]], false],
            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[txt]', 'FILES' => [['name' => 'txt'], ['name' => 'txt']]], false],

            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[php]', 'FILES' => []], true],
            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[php]', 'FILES' => [['name' => 'php']]], false],
            [['FILTER_FILES_ALLOWED_EXTENSIONS' => '[php]', 'FILES' => [['name' => 'php'], ['name' => 'php']]], false],
        ];
    }

    #[DataProvider('byteConvertDataProvider')]
    public function testByteConvert(int $input, string $output): void
    {
        $this->assertEquals($output, self::getMethod(FILESFilter::class, 'byteConvert')->invoke(null, $input));
    }

    public static function byteConvertDataProvider(): array
    {
        return [
            [0, '1B'],
            [1, '1B'],
            [2, '2B'],
            [1023, '1023B'],
            [1024, '1KB'],
            [1025, '1KB'],
            [2047, '2KB'],
            [1048575, '1024KB'],
            [1048576, '1MB'],
            [1048577, '1MB'],
            [1073741823, '1024MB'],
            [1073741824, '1GB'],
            [1073741825, '1GB'],
            [1099511627775, '1024GB'],
            [1099511627776, '1TB'],
            [1099511627777, '1TB'],
            [1125899906842623, '1PB'],
            [1125899906842624, '1PB'],
            [1125899906842625, '1PB'],
        ];
    }

    #[DataProvider('fileJsonEncodeDataProvider')]
    public function testFileJsonEncode(array $input, string $output): void
    {
        $this->assertEquals($output, self::getMethod(FILESFilter::class, 'fileJsonEncode')->invoke(null, $input));
    }

    public static function fileJsonEncodeDataProvider(): array
    {
        return [
            [['name' => 'file'], '{"name":"file","size":null,"type":null}'],
            [['name' => 'file', 'size' => 1], '{"name":"file","size":1,"type":null}'],
            [['name' => 'file', 'size' => 1, 'type' => 'text/plain'], '{"name":"file","size":1,"type":"text\/plain"}'],
            [['name' => 'file', 'size' => 1, 'type' => 'text/plain', 'tmp_name' => 'tmp'], '{"name":"file","size":1,"type":"text\/plain"}'],
            [['name' => 'file', 'size' => 1, 'type' => 'text/plain', 'tmp_name' => 'tmp', 'error' => 0], '{"name":"file","size":1,"type":"text\/plain"}'],
        ];
    }

    #[DataProvider('retrieveFilesDataProvider')]
    public function testRetrieveFiles(array $input, array $output): void
    {
        $this->assertEquals($output, self::getMethod(FILESFilter::class, 'retrieveFiles')->invoke(null, new Request(null, null, null, $input, null, null, null, null)));
    }

    public static function retrieveFilesDataProvider(): array
    {
        return [
            [[], []],
            [[['name' => 'file', 'type' => 'text/plain', 'tmp_name' => 'tmp', 'error' => 0, 'size' => 1]], [['name' => 'file', 'type' => 'text/plain', 'tmp_name' => 'tmp', 'error' => 0, 'size' => 1]]],
            [[['name' => 'file', 'type' => 'text/plain', 'tmp_name' => 'tmp', 'error' => 0, 'size' => 1], ['name' => 'file', 'type' => 'text/plain', 'tmp_name' => 'tmp', 'error' => 0, 'size' => 1]], [['name' => 'file', 'type' => 'text/plain', 'tmp_name' => 'tmp', 'error' => 0, 'size' => 1], ['name' => 'file', 'type' => 'text/plain', 'tmp_name' => 'tmp', 'error' => 0, 'size' => 1]]],

            [[
                'test' => [
                    'name' => ['file1', 'file2'],
                    'type' => ['text/plain', 'text/plain'],
                    'tmp_name' => ['tmp1', 'tmp2'],
                    'error' => [0, 0],
                    'size' => [1, 1]
                ]
            ], [
                ['name' => 'file1', 'type' => 'text/plain', 'tmp_name' => 'tmp1', 'error' => 0, 'size' => 1],
                ['name' => 'file2', 'type' => 'text/plain', 'tmp_name' => 'tmp2', 'error' => 0, 'size' => 1]
            ]],
            [[
                'test' => [
                    'name' => ['file1', 'file2'],
                    'type' => ['text/plain', 'text/plain'],
                    'tmp_name' => ['tmp1', 'tmp2'],
                    'error' => [0, 0],
                    'size' => [1, 1]
                ],
                'file' => [
                    'name' => ['file3', 'file4'],
                    'type' => ['text/plain', 'text/plain'],
                    'tmp_name' => ['tmp3', 'tmp4'],
                    'error' => [0, 0],
                    'size' => [1, 1]
                ]
            ], [
                ['name' => 'file1', 'type' => 'text/plain', 'tmp_name' => 'tmp1', 'error' => 0, 'size' => 1],
                ['name' => 'file2', 'type' => 'text/plain', 'tmp_name' => 'tmp2', 'error' => 0, 'size' => 1],
                ['name' => 'file3', 'type' => 'text/plain', 'tmp_name' => 'tmp3', 'error' => 0, 'size' => 1],
                ['name' => 'file4', 'type' => 'text/plain', 'tmp_name' => 'tmp4', 'error' => 0, 'size' => 1]
            ]],
        ];
    }
}