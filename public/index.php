<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Auto;

$auto = new Auto();
echo($auto->getSpeed());
//var_dump($_REQUEST);
//var_dump($_GET);
//var_dump($_POST);
//var_dump($_FILES);
//var_dump($_COOKIE);
//var_dump($_SESSION);
//var_dump($_SERVER);
//echo 'Hello World!';