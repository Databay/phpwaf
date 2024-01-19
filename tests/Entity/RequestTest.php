<?php

namespace App\Tests\Entity;

use App\Entity\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    #[DataProvider('getAndSetRequestDataProvider')]
    public function testGetAndSetRequest($input, $output): void
    {
        $request = new Request();
        $this->assertInstanceOf(Request::class, $request->setRequest($input));
        $this->assertEquals($output, $request->getRequest());
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
        $request = new Request();
        $this->assertInstanceOf(Request::class, $request->setGet($input));
        $this->assertEquals($output, $request->getGet());
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
        $request = new Request();
        $this->assertInstanceOf(Request::class, $request->setPost($input));
        $this->assertEquals($output, $request->getPost());
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
        $request = new Request();
        $this->assertInstanceOf(Request::class, $request->setFiles($input));
        $this->assertEquals($output, $request->getFiles());
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
        $request = new Request();
        $this->assertInstanceOf(Request::class, $request->setCookie($input));
        $this->assertEquals($output, $request->getCookie());
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
        $request = new Request();
        $this->assertInstanceOf(Request::class, $request->setSession($input));
        $this->assertEquals($output, $request->getSession());
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
        $request = new Request();
        $this->assertInstanceOf(Request::class, $request->setServer($input));
        $this->assertEquals($output, $request->getServer());
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
}