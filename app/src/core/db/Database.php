<?php

namespace Database;

use PDO;
use PDOException;
use Shared\{ErrorLogger, EnvParser};

class Database
{
	private PDO $pdo;

	public function __construct()
	{
		$envParser = new EnvParser(__DIR__ . '/../../../.env');
		$envFile = $envParser->parseFile();

		if (!$envFile) throw new \Exception("Failed to load .env file");

		$dbHost = $envFile['DB_HOST'];
		$dbName = $envFile['DB_NAME'];
		$dbUser = $envFile['DB_USER'];
		$dbPassword = $envFile['DB_PASSWORD'];

		try {
			$this->pdo = new PDO("pgsql:host={$dbHost};dbname={$dbName};user={$dbUser};password={$dbPassword};");
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $exc) {
			ErrorLogger::handleError(exc: $exc);
		}
	}

	public function returnPdo(): PDO
	{
		return $this->pdo;
	}
}