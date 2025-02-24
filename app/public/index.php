<?php

require_once __DIR__ . "../../../vendor/autoload.php";

use Router\Router;

$uri = $_SERVER['REQUEST_URI'];
$methodType = $_SERVER['REQUEST_METHOD'];

if ($uri === '/authentication' || $uri === '/user' ) {
	$router = new Router();
	$router->handleRequest(uri: $uri, methodType: $methodType);

} else {
	require_once __DIR__ . '../../src/modules/views/index.html';
}