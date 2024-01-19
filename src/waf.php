<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\Request;
use App\Handler\RequestHandler;
use App\Service\ConfigLoader;

define('CONFIG', ConfigLoader::loadConfig());

if (defined('CONFIG') && isset(CONFIG['WAF_ACTIVE']) && CONFIG['WAF_ACTIVE'] === 'true') {
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

    echo $filtersOutput ? 'Not filtered' : 'Filtered';
}