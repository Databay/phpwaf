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

$filterTriggered = RequestHandler::handleRequest($request);

if ($filterTriggered) {
    echo 'Filtered';
} else {
    echo 'Passed';
}