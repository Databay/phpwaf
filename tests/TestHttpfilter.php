<?php

namespace App\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\Request;
use App\Filter\HttpFilter;
use PHPUnit\Framework\TestCase;

class TestHttpfilter extends TestCase
{
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
		$httpFilter = new HttpFilter();
        $this->assertEquals($httpFilter->apply($request), true);

		$request->setServer("");
		$this->assertEquals($httpFilter->apply($request), false);

		$_SERVER["HTTPS"] = "";
		$request->setServer($_SERVER);
		$this->assertEquals($httpFilter->apply($request), false);

		unset($_SERVER["HTTPS"]);
		$request->setServer($_SERVER);
		$this->assertEquals($httpFilter->apply($request), false);
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
		$tEnd_nanoseconds = (int) (microtime(true) * 1000000000);
		$tDiff = $tEnd_nanoseconds - $tStart_nanoseconds;

		// 20 mikrosekunden
		$this->assertLessThan( 20000, $tDiff, 'Took too long. From: ' . $tStart_nanoseconds . ' to ' . $tEnd_nanoseconds );
    }
}