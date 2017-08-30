<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'vendor/autoload.php';

$route = new App\Router($_SERVER);

$route->get('/api/*', 'Api@index');

$route->get('/', function () {
    $api = new App\Api();
    $history = $api->episodes();
    $feeds = $api->feeds();
    $response = require('src/Views/home.php');

    return $response;
});

$route->get('/test', function () {
    $response = require('src/Views/svgtest.php');

    return $response;
});

header("HTTP/1.0 404 Not Found");
