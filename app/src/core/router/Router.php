<?php

namespace Router;

use Utils\ErrorLogger;

class Router
{
	public function handleRequest(string $uri): void
	{
		try {
			$uriSegments = explode('/', trim($uri, '/'), 2);

			$controllerClass = 'Controllers\\' . ucfirst($uriSegments[0]) . 'Controller';

			if (!class_exists($controllerClass)) throw new \Exception('Controller not found: ' . $controllerClass);

			$controller = new $controllerClass();
			$method = $uriSegments[1];

			if (!method_exists($controller, $method)) throw new \Exception('Method not found: ' . $method);

			header("Content-Type: application/json");
			http_response_code(200);
			echo $controller->$method();

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(404);
			header("Content-Type: application/json");
			echo json_encode(['error' => $exc->getMessage()]);
		}
	}
}