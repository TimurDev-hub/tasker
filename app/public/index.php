<?php

require_once __DIR__ . "../../../vendor/autoload.php";

use Router\Router;

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

if (strpos($uri, '/api') !== false && strpos($uri, '/api') === 0) {
	$router = new Router(uri: $uri, httpMethod: $httpMethod);
	$router->handleRequest();

} else {
	require_once __DIR__ . '../../src/modules/views/index.html';
}