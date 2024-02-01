<?php

namespace App\Tests\Entity;

use App\Entity\Request;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class RequestTest extends BaseTestCase
{
    private Request $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new Request([], [], [], [], [], [], [], []);
    }

    #[DataProvider('getAndSetRequestDataProvider')]
    public function testGetAndSetRequest($input, $output): void
    {
        $this->assertInstanceOf(Request::class, $this->request->setRequest($input));
        $this->assertEquals($output, $this->request->getRequest());
    }

    public static function getAndSetRequestDataProvider(): array
    {
        return [
            ['test', 'test'],
            ['', ''],
            [1, 1],
            [null, null],
            [0.1, 0.1],
            [true, true],
            [false, false],
            [[], []],
            [(object) [], (object) []],
        ];
    }

    #[DataProvider('getAndSetGetDataProvider')]
    public function testSetAndGet($input, $output): void
    {
        $this->assertInstanceOf(Request::class, $this->request->setGet($input));
        $this->assertEquals($output, $this->request->getGet());
    }

    public static function getAndSetGetDataProvider(): array
    {
        return [
            ['test', 'test'],
            ['', ''],
            [1, 1],
            [null, null],
            [0.1, 0.1],
            [true, true],
            [false, false],
            [[], []],
            [(object) [], (object) []],
        ];
    }

    #[DataProvider('getAndSetPostDataProvider')]
    public function testSetAndPost($input, $output): void
    {
        $this->assertInstanceOf(Request::class, $this->request->setPost($input));
        $this->assertEquals($output, $this->request->getPost());
    }

    public static function getAndSetPostDataProvider(): array
    {
        return [
            ['test', 'test'],
            ['', ''],
            [1, 1],
            [null, null],
            [0.1, 0.1],
            [true, true],
            [false, false],
            [[], []],
            [(object) [], (object) []],
        ];
    }

    #[DataProvider('getAndSetFilesDataProvider')]
    public function testSetAndGetFiles($input, $output): void
    {
        $this->assertInstanceOf(Request::class, $this->request->setFiles($input));
        $this->assertEquals($output, $this->request->getFiles());
    }

    public static function getAndSetFilesDataProvider(): array
    {
        return [
            ['test', 'test'],
            ['', ''],
            [1, 1],
            [null, null],
            [0.1, 0.1],
            [true, true],
            [false, false],
            [[], []],
            [(object) [], (object) []],
        ];
    }

    #[DataProvider('getAndSetCookieDataProvider')]
    public function testSetAndGetCookie($input, $output): void
    {
        $this->assertInstanceOf(Request::class, $this->request->setCookie($input));
        $this->assertEquals($output, $this->request->getCookie());
    }

    public static function getAndSetCookieDataProvider(): array
    {
        return [
            ['test', 'test'],
            ['', ''],
            [1, 1],
            [null, null],
            [0.1, 0.1],
            [true, true],
            [false, false],
            [[], []],
            [(object) [], (object) []],
        ];
    }

    #[DataProvider('getAndSetSessionDataProvider')]
    public function testSetAndGetSession($input, $output): void
    {
        $this->assertInstanceOf(Request::class, $this->request->setSession($input));
        $this->assertEquals($output, $this->request->getSession());
    }

    public static function getAndSetSessionDataProvider(): array
    {
        return [
            ['test', 'test'],
            ['', ''],
            [1, 1],
            [null, null],
            [0.1, 0.1],
            [true, true],
            [false, false],
            [[], []],
            [(object) [], (object) []],
        ];
    }

    #[DataProvider('getAndSetServerDataProvider')]
    public function testSetAndGetServer($input, $output): void
    {
        $this->assertInstanceOf(Request::class, $this->request->setServer($input));
        $this->assertEquals($output, $this->request->getServer());
    }

    public static function getAndSetServerDataProvider(): array
    {
        return [
            ['test', 'test'],
            ['', ''],
            [1, 1],
            [null, null],
            [0.1, 0.1],
            [true, true],
            [false, false],
            [[], []],
            [(object) [], (object) []],
        ];
    }

    #[DataProvider('getAndSetHeadersDataProvider')]
    public function testSetAndGetHeaders($input, $output): void
    {
        $this->assertInstanceOf(Request::class, $this->request->setHeaders($input));
        $this->assertEquals($output, $this->request->getHeaders());
    }

    public static function getAndSetHeadersDataProvider(): array
    {
        return [
            ['test', 'test'],
            ['', ''],
            [1, 1],
            [null, null],
            [0.1, 0.1],
            [true, true],
            [false, false],
            [[], []],
            [(object) [], (object) []],
        ];
    }
}