<?php

namespace Router;

use Utils\ErrorLogger;

class Router
{
	public function handleRequest(string $uri, string $methodType): void
	{
		try {
			$uriSegments = explode('/', trim($uri, '/'));
			$controllerName = ucfirst(array_shift($uriSegments)) . 'Controller';

			// fixing
			file_put_contents(__DIR__ . '/../../../../index.txt', $controllerName . PHP_EOL, FILE_APPEND);

			$controllerClass = 'Controllers\\' . $controllerName;

			if (!class_exists($controllerClass)) throw new \Exception('Controller not found: ' . $controllerName);

			$controller = new $controllerClass();
			$method = strtolower($methodType);

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