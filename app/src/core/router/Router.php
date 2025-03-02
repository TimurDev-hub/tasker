<?php

namespace Router;

use Utils\ErrorLogger;

class Router
{
	private static function parseUri(string $uri): string
	{
		return trim(str_replace('/api', '', $uri), '/');
	}

	private static function getControllersMap(): array
	{
		return [
			'authentication' => 'Controllers\\AuthenticationController',
			'user' => 'Controllers\\UserController',
			'task' => 'Controllers\\TaskController'
		];
	}

	private static function getController(string $uri): string
	{
		return self::getControllersMap()[self::parseUri(uri: $uri)];
	}

	private static function getMethod(): array
	{
		return [
			'authentication' => [
				'POST' => 'login',
				'DELETE' => 'logout'
			],
			'user' => [
				'POST' => 'registerUser',
				'DELETE' => 'deleteUser'
			],
			'task' => [
				'POST' => 'createTask',
				'GET' => 'getTasks',
				'DETELE' => 'deleteTask'
			]
		];
	}

	public function handleRequest(string $uri, string $methodType): void
	{
		try {
			$resourceName = self::parseUri(uri: $uri);
			$controllerClass = self::getController(uri: $uri);

			if (!class_exists($controllerClass)) throw new \Exception('Controller not found: ' . $controllerClass);

			$controller = new $controllerClass();
			$method = self::getMethod()[$resourceName][$methodType];

			if (!method_exists($controller, $method)) throw new \Exception('Method not found: ' . $method);

			http_response_code(200);
			echo $controller->$method();

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(404);
			echo json_encode(['error' => $exc->getMessage()]);
		}
	}
}