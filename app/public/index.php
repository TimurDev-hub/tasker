<?php

require_once __DIR__ . "../../../vendor/autoload.php";

use Router\Router;

$uri = $_SERVER['REQUEST_URI'];

if (strpos($uri, '/api') !== false) {
	$router = new Router();
	$router->handleRequest(uri: $uri);

} else {
	require_once __DIR__ . '../../src/modules/views/index.html';
}