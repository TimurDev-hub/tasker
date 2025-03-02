<?php

header("Content-Type: application/json");

require_once __DIR__ . "../../../vendor/autoload.php";

use Router\Router;

$uri = $_SERVER['REQUEST_URI'];
$methodType = $_SERVER['REQUEST_METHOD'];

if (strpos($uri, '/api') !== false && strpos($uri, '/api') === 0) {
	$router = new Router(uri: $uri);
	$router->handleRequest(methodType: $methodType);

} else {
	require_once __DIR__ . '../../src/modules/views/index.html';
}