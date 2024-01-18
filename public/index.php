<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\Request;
use App\Handler\RequestHandler;

$request = new Request(
    $_REQUEST,
    $_GET,
    $_POST,
    $_FILES,
    $_COOKIE,
    $_SESSION,
    $_SERVER
);

$filtersOutput = RequestHandler::handleRequest($request);

if (!$filtersOutput) {
    echo 'Filtered';
} else {
    echo 'Passed';
}

echo PHP_EOL . '<h1>Hello world!</h1>';