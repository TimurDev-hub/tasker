<?php

namespace Router;

use Utils\ErrorLogger;

class Router
{
	private static string $resource;
	private static string $item;

	private static array $controllersMap;
	private static array $methodsMap;

	public function __construct(string $uri)
	{
		self::$resource = self::parseUri(uri: $uri)['resource'];
		self::$item = self::parseUri(uri: $uri)['item'];

		self::$controllersMap = self::getResourcesTable();
		self::$methodsMap = self::getMethodsMaps();
	}

	private static function getControllersMaps(): array
	{
		return [
			'authentication' => 'Controllers\\AuthenticationController',
			'user' => 'Controllers\\UserController',
			'task' => 'Controllers\\TaskController'
		];
	}

	private static function getMethodsMaps(): array
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
				'DELETE' => 'deleteTask'
			]
		];
	}

	private static function getResourcesTable(): array
	{
		return [
			'authentication' => [
				'controller' => 'Controllers\\AuthenticationController',
				'method' => [
					'POST' => 'login',
					'DELETE' => 'logout'
				]
			],
			'user' => [
				'controller' => 'Controllers\\UserController',
				'method' => [
					'POST' => 'registerUser',
					'DELETE' => 'deleteUser'
				]
			],
			'task' => [
				'controller' => 'Controllers\\TaskController',
				'method' => [
					'POST' => 'createTask',
					'GET' => 'getTasks',
					'DELETE' => 'deleteTask'
				]
			]
		];
	}

	private static function parseUri(string $uri): array
	{
		list($prefix, $resource, $item) = explode('/', trim($uri, '/'), 3) + [null, null, null];

		return [
			'prefix' => $prefix,
			'resource' => $resource,
			'item' => $item
		];
	}

	private static function getController(): string|false
	{
		return self::$controllersMap[self::$resource] ?? false;
	}

	private static function getMethod(string $methodType): string|false
	{
		return self::$methodsMap[self::$resource][$methodType] ?? false;
	}

	public function handleRequest(string $methodType): void
	{
		try {
			$controllerClass = self::getController();

			if (!$controllerClass) throw new \Exception('Controller not found: ' . $controllerClass);

			$controller = new $controllerClass();
			$method = self::getMethod(methodType: $methodType);

			if (!$method || !method_exists($controller, $method)) throw new \Exception('Method not found: ' . $method);

			http_response_code(200);
			echo $controller->$method(self::$item);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(404);
			echo json_encode(['error' => $exc->getMessage()]);
		}
	}
}