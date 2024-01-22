<?php

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
        $_SERVER
    );

    echo (RequestHandler::handleRequest($request) ? 'Not filtered' : 'Filtered');
}