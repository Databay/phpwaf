<?php

namespace Abstracts;

use App\Abstracts\AbstractFileLoader;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class FileLoaderTest extends BaseTestCase
{
    #[DataProvider('removeCommentsDataProvider')]
    public function testRemoveComments(string $input, string $output): void
    {
        $removeComments = self::getMethod(AbstractFileLoader::class, 'removeComments');
        $this->assertEquals($output, $removeComments->invokeArgs(null, [$input]));
    }

    public static function removeCommentsDataProvider(): array
    {
        return [
            ['#test', ''],
            [' #test', ' '],
            ['# test', ''],
            [' # test', ' '],
            ['test#test', 'test'],
            ['test #test', 'test '],
            ['test# test', 'test'],
            ['test # test', 'test '],
            [' test#test', ' test'],
            [' test #test', ' test '],
            [' test# test', ' test'],
            [' test # test', ' test '],
        ];
    }
}