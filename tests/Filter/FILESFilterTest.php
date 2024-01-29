<?php

namespace App\Tests\Filter;

use App\Entity\Request;
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
            'FILTER_FILES_BLOCKED_EXTENSIONS' => $input['FILTER_FILES_BLOCKED_EXTENSIONS'] ?? 'null',
        ]);

        $request = new Request(null, null, null, $input['FILES'], null, null, null);
        $this->assertEquals($output, (new FILESFilter())->apply($request));
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

            [['FILTER_FILES_BLOCKED_EXTENSIONS' => 'null', 'FILES' => []], true],
            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[]', 'FILES' => []], true],

            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[php]', 'FILES' => []], true],
            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[php]', 'FILES' => [['name' => '.txt']]], true],
            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[php]', 'FILES' => [['name' => '.txt'], ['name' => '.txt']]], true],

            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[txt]', 'FILES' => []], true],
            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[txt]', 'FILES' => [['name' => '.txt']]], false],
            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[txt]', 'FILES' => [['name' => '.txt'], ['name' => '.txt']]], false],

            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[php]', 'FILES' => []], true],
            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[php]', 'FILES' => [['name' => '.php']]], false],
            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[php]', 'FILES' => [['name' => '.php'], ['name' => '.php']]], false],

            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[txt]', 'FILES' => []], true],
            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[txt]', 'FILES' => [['name' => '.php']]], true],
            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[txt]', 'FILES' => [['name' => '.php'], ['name' => '.php']]], true],

            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[php]', 'FILES' => []], true],
            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[php]', 'FILES' => [['name' => 'php']]], false],
            [['FILTER_FILES_BLOCKED_EXTENSIONS' => '[php]', 'FILES' => [['name' => 'php'], ['name' => 'php']]], false],
        ];
    }
}