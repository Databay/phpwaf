<?php

namespace App\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\Request;
use App\Filter\HttpFilter;
use PHPUnit\Framework\TestCase;
use App\Service\ConfigLoader;

class TestHttpfilter extends TestCase
{

	public function setUp(): void {
		if(!defined("CONFIG")){
			define('CONFIG', ConfigLoader::loadConfig());
		}
	}

	public function testApply()
    {
		$_SERVER["HTTPS"] = "on";
        $request = new Request(
			"",
			"",
			"",
			"",
			"",
			"",
			$_SERVER
		);
		// ALLOW
		$httpFilter = new HttpFilter();
        $this->assertEquals(true, $httpFilter->apply($request));


		// BLOCK
		$request->setServer("");
		$this->assertEquals(false, $httpFilter->apply($request));

		$_SERVER["HTTPS"] = "";
		$request->setServer($_SERVER);
		$this->assertEquals(false, $httpFilter->apply($request));

		unset($_SERVER["HTTPS"]);
		$request->setServer($_SERVER);
		$this->assertEquals(false, $httpFilter->apply($request));
    }

	public function testPerformance()
    {
		$_SERVER["HTTPS"] = "on";
		$tStart_nanoseconds = (int) (microtime(true) * 1000000000);
        $request = new Request(
			"",
			"",
			"",
			"",
			"",
			"",
			$_SERVER
		);
		$httpFilter = new HttpFilter();
		$res = $httpFilter->apply($request);
		$tEnd_nanoseconds = (int) (microtime(true) * 1000000000);
		$tDiff = $tEnd_nanoseconds - $tStart_nanoseconds;

		$this->assertEquals(true, $res);
		// 20 mikrosekunden
		$this->assertLessThan( 20000, $tDiff, 'Took too long. From: ' . $tStart_nanoseconds . ' to ' . $tEnd_nanoseconds );
    }
}