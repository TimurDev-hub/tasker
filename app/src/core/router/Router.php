<?php

namespace Router;

use Utils\ErrorLogger;

class Router
{
	private ?string $uri;
	private ?string $httpMethod;

	private ?string $resource = null;
	private ?string $item = null;

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

	public function __construct(string $uri, string $httpMethod)
	{
		$this->uri = $uri;
		$this->httpMethod = $httpMethod;

		$parsedUri = $this->parseUri();

		$this->resource = $parsedUri['resource'];
		$this->item = $parsedUri['item'];
	}

	private function prepareUri(): string|false
	{
		return trim(str_replace('/api', '', $this->uri), '/') ?? false;
	}

	private function parseUri(): array
	{
		$preparedUri = $this->prepareUri();

		if (!$preparedUri) {
			return [
				'resource' => null,
				'item' => null
			];
		}

		list($resource, $item) = explode('/', $preparedUri, 2) + [null, null];

		return [
			'resource' => $resource,
			'item' => $item
		];
	}

	private function getResource(): ?array
	{
		return $this->resourcesMap[$this->resource] ?? null;
	}

	private function getController(): string|false
	{
		$uriResource = $this->getResource();
		if (!$uriResource) return false;
		return $uriResource['controller'];
	}

	private function getMethod(): string|false
	{
		$uriResource = $this->getResource();
		if (!$uriResource) return false;
		return $uriResource['method'][$this->httpMethod] ?? false;
	}

	public function handleRequest(): void
	{
		header("Content-Type: application/json");

		try {
			$controllerClass = $this->getController();

			if (!$controllerClass) throw new \Exception('Controller not found: ' . $controllerClass);

			$controller = new $controllerClass();
			$method = $this->getMethod();

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