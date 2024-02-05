<?php

//$startTime = microtime(true);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\Request;
use App\Handler\RequestHandler;
use App\Service\ConfigLoader;

if (define('CONFIG', ConfigLoader::loadConfig()) && CONFIG['WAF_ACTIVE'] === 'true') {
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

    echo (RequestHandler::handleRequest($request) ? 'Not filtered' : 'Filtered');
}

//$executionTime = round((microtime(true) - $startTime) * 1000, 3);

//echo '<br>Execution time: ' . $executionTime . ' ms';

//file_put_contents(__DIR__ . '/../times.txt', $executionTime . PHP_EOL, FILE_APPEND);