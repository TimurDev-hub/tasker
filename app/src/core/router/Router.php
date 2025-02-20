<?php

namespace Router;

use Shared\ErrorLogger;

class Router
{
	public function routerStarter(string $uri, string $method): void
	{
		try {
			$uriP = explode('/', trim($uri, '/'));
			$controllerName = ucfirst(array_shift($uriP)) . 'Controller';

			$controllerClass = '\\Controllers\\' . $controllerName;

			if (!class_exists($controllerClass)) throw new \Exception('Controller not found: ' . $controllerName);

			$controller = new $controllerClass();

			switch ($method) {
				case 'GET':
					$methodName = 'get';
					break;
				case 'POST':
					$methodName = 'post';
					break;
				case 'PUT':
					$methodName = 'put';
					break;
				case 'DELETE':
					$methodName = 'delete';
					break;
			}

			if (!method_exists($controller, $methodName)) throw new \Exception("Method not found: " . $methodName);

			header('Content-Type: application/json');
			echo $controller->$methodName();

		} catch (\Exception $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(404);
			header('Content-Type: application/json');
			echo json_encode(['error' => $exc->getMessage()]);
		}
	}
}