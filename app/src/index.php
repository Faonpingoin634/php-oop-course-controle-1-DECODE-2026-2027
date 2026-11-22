<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Lib\Http\Router;
use App\Lib\Http\Request;

$request = new Request();

$router = new Router();
$router->main($request);

$response = $router->main($request);

$response->send();


