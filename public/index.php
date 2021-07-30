<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__.'/../vendor/autoload.php';

$route = new App\Router($_SERVER);

$route->get('/api/*', 'Api@index');

$route->get('/', function () {
    return require(__DIR__.'/../src/Views/home.php');
});

header("HTTP/1.0 404 Not Found");
