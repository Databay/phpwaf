<?php

namespace App\Tests\Filter;

use App\Entity\Request;
use App\Filter\RequestMethodFilter;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class RequestMethodFilterTest extends BaseTestCase
{
    #[DataProvider('applyDataProvider')]
    public function testApply(array $input, bool $output): void
    {
        $request = new Request(null, null, null, null, null, null, $input['SERVER'] ?? []);
        define('CONFIG', array_merge(['FILTER_REQUESTMETHOD_ACTIVE' => 'true'], $input));
        $this->assertEquals($output, (new RequestMethodFilter())->apply($request));
    }

    public static function applyDataProvider(): array
    {
        return [
            [['FILTER_REQUESTMETHOD_ACTIVE' => 'false'], true],
            [['FILTER_REQUESTMETHOD_ACTIVE' => 'INVALID'], true],

            [['FILTER_REQUESTMETHOD_GET_ALLOW' => 'true', 'SERVER' => ['REQUEST_METHOD' => 'GET']], true],
            [['FILTER_REQUESTMETHOD_GET_ALLOW' => 'false', 'SERVER' => ['REQUEST_METHOD' => 'GET']], false],
            [['FILTER_REQUESTMETHOD_GET_ALLOW' => 'INVALID', 'SERVER' => ['REQUEST_METHOD' => 'GET']], true],

            [['FILTER_REQUESTMETHOD_POST_ALLOW' => 'true', 'SERVER' => ['REQUEST_METHOD' => 'POST']], true],
            [['FILTER_REQUESTMETHOD_POST_ALLOW' => 'false', 'SERVER' => ['REQUEST_METHOD' => 'POST']], false],
            [['FILTER_REQUESTMETHOD_POST_ALLOW' => 'INVALID', 'SERVER' => ['REQUEST_METHOD' => 'POST']], true],

            [['FILTER_REQUESTMETHOD_PUT_ALLOW' => 'true', 'SERVER' => ['REQUEST_METHOD' => 'PUT']], true],
            [['FILTER_REQUESTMETHOD_PUT_ALLOW' => 'false', 'SERVER' => ['REQUEST_METHOD' => 'PUT']], false],
            [['FILTER_REQUESTMETHOD_PUT_ALLOW' => 'INVALID', 'SERVER' => ['REQUEST_METHOD' => 'PUT']], true],

            [['FILTER_REQUESTMETHOD_PATCH_ALLOW' => 'true', 'SERVER' => ['REQUEST_METHOD' => 'PATCH']], true],
            [['FILTER_REQUESTMETHOD_PATCH_ALLOW' => 'false', 'SERVER' => ['REQUEST_METHOD' => 'PATCH']], false],
            [['FILTER_REQUESTMETHOD_PATCH_ALLOW' => 'INVALID', 'SERVER' => ['REQUEST_METHOD' => 'PATCH']], true],

            [['FILTER_REQUESTMETHOD_DELETE_ALLOW' => 'true', 'SERVER' => ['REQUEST_METHOD' => 'DELETE']], true],
            [['FILTER_REQUESTMETHOD_DELETE_ALLOW' => 'false', 'SERVER' => ['REQUEST_METHOD' => 'DELETE']], false],
            [['FILTER_REQUESTMETHOD_DELETE_ALLOW' => 'INVALID', 'SERVER' => ['REQUEST_METHOD' => 'DELETE']], true],

            [['FILTER_REQUESTMETHOD_OPTIONS_ALLOW' => 'true', 'SERVER' => ['REQUEST_METHOD' => 'OPTIONS']], true],
            [['FILTER_REQUESTMETHOD_OPTIONS_ALLOW' => 'false', 'SERVER' => ['REQUEST_METHOD' => 'OPTIONS']], false],
            [['FILTER_REQUESTMETHOD_OPTIONS_ALLOW' => 'INVALID', 'SERVER' => ['REQUEST_METHOD' => 'OPTIONS']], true],

            [['FILTER_REQUESTMETHOD_HEAD_ALLOW' => 'true', 'SERVER' => ['REQUEST_METHOD' => 'HEAD']], true],
            [['FILTER_REQUESTMETHOD_HEAD_ALLOW' => 'false', 'SERVER' => ['REQUEST_METHOD' => 'HEAD']], false],
            [['FILTER_REQUESTMETHOD_HEAD_ALLOW' => 'INVALID', 'SERVER' => ['REQUEST_METHOD' => 'HEAD']], true],
        ];
    }
}