<?php

namespace App\Tests;

use App\Entity\Request;
use App\Filter\HttpFilter;
use PHPUnit\Framework\TestCase;
use App\Service\ConfigLoader;

class HttpFilterTest extends TestCase
{
	public function setUp(): void
    {
		if(!defined("CONFIG")){
			define('CONFIG', ConfigLoader::loadConfig());
		}
	}

	public function testApply()
    {
		$_SERVER['HTTPS'] = 'on';
        $request = new Request(
			'',
			'',
			'',
			'',
			'',
			'',
			$_SERVER
		);
		// ALLOW
		$httpFilter = new HttpFilter();
        $this->assertTrue($httpFilter->apply($request));

		// BLOCK
		$request->setServer('');
		$this->assertFalse($httpFilter->apply($request));

		$_SERVER['HTTPS'] = '';
		$request->setServer($_SERVER);
		$this->assertFalse($httpFilter->apply($request));

		unset($_SERVER["HTTPS"]);
		$request->setServer($_SERVER);
		$this->assertFalse($httpFilter->apply($request));
    }

	public function testPerformance()
    {
		$_SERVER['HTTPS'] = 'on';
        $nanosecondsStartTime = (int) (microtime(true) * 1000000000);
        $request = new Request(
			'',
			'',
			'',
			'',
			'',
			'',
			$_SERVER
		);
		$httpFilter = new HttpFilter();
		$res = $httpFilter->apply($request);
		$nanosecondsEndTime = (int) (microtime(true) * 1000000000);

		$this->assertEquals(true, $res);
		// 20 mikrosekunden
		$this->assertLessThan( 20000, ($nanosecondsEndTime - $nanosecondsStartTime), 'Took too long. From: ' . $nanosecondsStartTime . ' to ' . $nanosecondsEndTime );
    }
}