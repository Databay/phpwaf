<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\Request;
use App\Handler\RequestHandler;
use App\Service\ConfigLoader;

define('CONFIG', ConfigLoader::loadConfig());

$request = new Request(
    $_REQUEST,
    $_GET,
    $_POST,
    $_FILES,
    $_COOKIE,
    $_SESSION,
    $_SERVER
);

$currentMilliSecond = (int) (microtime(true) * 1000);
echo 'CUR MILLISECONDS:'.$currentMilliSecond.PHP_EOL;

$currentMicroSecond = (int) (microtime(true) * 1000000);
echo 'CUR MICROSECONDS:'.$currentMicroSecond.PHP_EOL;
print_r(microtime(true));

$filtersOutput = RequestHandler::handleRequest($request);

if (!$filtersOutput) {
    echo 'Filtered';
} else {
    echo 'Passed';
}

echo PHP_EOL . '<h1>Hello world!</h1>';