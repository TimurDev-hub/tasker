<?php

namespace Router;

use Utils\ErrorLogger;

class Router
{
	private string $resource;
	private ?string $item;

	private const CONTROLLER = 'controller';
	private const METHOD = 'method';

	private array $resourcesMap = [
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

	public function __construct(string $uri)
	{
		$parsedUri = $this->parseUri(uri: $uri);

		$this->resource = $parsedUri['resource'];
		$this->item = $parsedUri['item'];
	}

	private function parseUri(string $uri): array
	{
		list($prefix, $resource, $item) = explode('/', trim($uri, '/'), 3) + [null, null, null];

		return [
			'prefix' => $prefix,
			'resource' => $resource,
			'item' => $item
		];
	}

	private function getResource(): string|false
	{
		return $this->resourcesMap[$this->resource] ?? false;
	}

	private function getController(): string|false
	{
		$uriResource = $this->getResource();
		if (!$uriResource) return false;
		return $uriResource[self::CONTROLLER];
	}

	private function getMethod(string $methodType): string|false
	{
		$uriResource = $this->getResource();
		if (!$uriResource) return false;
		return $uriResource[self::METHOD][$methodType] ?? false;
	}

	public function handleRequest(string $methodType): void
	{
		try {
			$controllerClass = $this->getController();

			if (!$controllerClass) throw new \Exception('Controller not found: ' . $controllerClass);

			$controller = new $controllerClass();
			$method = $this->getMethod(methodType: $methodType);

			if (!$method || !method_exists($controller, $method)) throw new \Exception('Method not found: ' . $method);

			http_response_code(200);
			echo $controller->$method($this->item);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(404);
			echo json_encode(['error' => $exc->getMessage()]);
		}
	}
}