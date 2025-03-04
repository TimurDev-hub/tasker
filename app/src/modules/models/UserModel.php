<?php

namespace Models;

use Templates\TemplateModel;
use Utils\ErrorLogger;

class UserModel extends TemplateModel
{
	private \PDO $pdo;
	private ?array $userData;

	public function __construct(\PDO $pdo, ?array $userData = null)
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
		if (!isset($this->userData['user_name'])) return false;

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
		if (!isset($this->userData['user_name'], $this->userData['user_password'])) return false;

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

	public function deleteAccount(int $userId): bool
	{
		if (!isset($userId) || !is_integer($userId)) return false;

		try {
			$this->pdo->beginTransaction();

			$stmt = $this->pdo->prepare("DELETE FROM tasks WHERE user_id = ?");
			if (!$stmt->execute([$userId])) throw new \Exception('Failed to delete tasks from tasks table');

			$stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = ?");
			if (!$stmt->execute([$userId])) throw new \Exception('Failed to delete user from users table');

			$this->pdo->commit();
			return true;

		} catch (\Throwable $exc) {
			if ($this->pdo->inTransaction()) $this->pdo->rollBack();
			ErrorLogger::handleError($exc);
			return false;
		}
	}
}