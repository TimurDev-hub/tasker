<?php

namespace Models;

use Templates\TemplateModel;
use Utils\ErrorLogger;

class UserModel extends TemplateModel
{
	private \PDO $pdo;
	private array $userData;

	public function __construct(\PDO $pdo, array $userData)
	{
		$this->pdo = $pdo;
		$this->userData = $userData;
	}

	private function checkAccount(): bool
	{
		try {
			$stmt = $this->pdo->prepare("SELECT 1 FROM users WHERE user_name = ? LIMIT 1");
			$stmt->execute([$this->userData['user_name']]);
			return $stmt->fetchColumn() > 0;

		} catch (\Throwable $exc) {
			ErrorLogger::handleError($exc);
			return true;
		}
	}

	public function getUserByName(): array|false
	{
		if (!$this->prepareData(data: $this->userData)) return false;
		if (!$this->validateData(data: $this->userData)) return false;

		try {
			$stmt = $this->pdo->prepare("SELECT user_id, user_name, user_password FROM users WHERE user_name = ?");
			$stmt->execute([$this->userData['user_name']]);
			return $stmt->fetch(\PDO::FETCH_ASSOC);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}

	public function createAccount(): bool
	{
		if (!$this->prepareData(data: $this->userData)) return false;
		if (!$this->validateData(data: $this->userData)) return false;
		if ($this->checkAccount()) return false;

		$hashedPassword = password_hash($this->userData['user_password'], PASSWORD_DEFAULT);

		try {
			$stmt = $this->pdo->prepare("INSERT INTO users (user_name, user_password) VALUES (?, ?)");
			return $stmt->execute([$this->userData['user_name'], $hashedPassword]);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}

	public function deleteAccount(): bool
	{
		if (!$this->prepareData(data: $this->userData)) return false;
		if (!$this->validateData(data: $this->userData)) return false;

		try {
			$stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = ?");
			return $stmt->execute([$this->userData['user_id']]);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}
}