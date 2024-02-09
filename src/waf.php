<?php

//$startTime = microtime(true);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Abstracts\AbstractFileLoader;
use App\Entity\Request;
use App\Handler\RequestHandler;
use App\Service\ConfigLoader;

if (define('CONFIG', ConfigLoader::load()) && CONFIG['WAF_ACTIVE'] === 'true') {
    AbstractFileLoader::checkOPcache();

    $request = new Request(
        $_REQUEST,
        $_GET,
        $_POST,
        $_FILES,
        $_COOKIE,
        $_SESSION,
        $_SERVER,
        getallheaders()
    );

    $response = RequestHandler::handleRequest($request) ? 'Not filtered' : 'Filtered';
//    echo $response;
}

//$executionTime = round((microtime(true) - $startTime) * 1000, 3);

//echo '<br>Execution time: ' . $executionTime . ' ms';

//file_put_contents(__DIR__ . '/../times.txt', $executionTime . PHP_EOL, FILE_APPEND);