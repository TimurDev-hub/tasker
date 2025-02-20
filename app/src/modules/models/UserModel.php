<?php

namespace Models;

use PDO;
use Shared\ErrorLogger;
use Throwable;

class UserModel
{
	private const MIN_NAME_SIZE = 4;
	private const MAX_NAME_SIZE = 16;
	private const MIN_PASS_SIZE = 12;
	private const MAX_PASS_SIZE = 24;

	private PDO $pdo;
	private array $userArray;

	public function __construct(PDO $pdo, array $userArray)
	{
		$this->pdo = $pdo;
		$this->userArray = $userArray;
	}

	private function prepareData(): bool
	{
		if (empty($this->userArray)) return false;

		foreach ($this->userArray as &$item) {
			$item = strval(htmlspecialchars(trim($item)));
		}

		return true;
	}

	private function validateData(): bool
	{
		foreach ($this->userArray as $key => $item) {
			switch ($key) {
				case 'user_id':
					if (empty($item)) return false;
					elseif (!is_numeric($item)) return false;
					break;
				case 'user_name':
					if (empty($item)) return false;
					elseif (strlen($item) < self::MIN_NAME_SIZE || strlen($item) > self::MAX_NAME_SIZE) return false;
					break;
				case 'user_password':
					if (empty($item)) return false;
					elseif (strlen($item) < self::MIN_PASS_SIZE || strlen($item) > self::MAX_PASS_SIZE) return false;
					break;
			}
		}

		return true;
	}

	private function checkAccount(): bool
	{
		try {
			$stmt = $this->pdo->prepare("SELECT 1 FROM users WHERE user_name = ? LIMIT 1");
			$stmt->execute([$this->userArray['user_name']]);
			return $stmt->fetchColumn() > 0;

		} catch (Throwable $exc) {
			ErrorLogger::handleError($exc);
			return true;
		}
	}

	public function getUserByName(): array|false
	{
		if (!$this->prepareData()) return false;
		if (!$this->validateData()) return false;

		try {
			$stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_name = ?");
			$stmt->execute([$this->userArray['user_name']]);
			return $stmt->fetch(PDO::FETCH_ASSOC);

		} catch (Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}

	public function createAccount(): bool
	{
		if (!$this->prepareData()) return false;
		if (!$this->validateData()) return false;
		if ($this->checkAccount()) return false;

		$hashedPassword = password_hash($this->userArray['user_password'], PASSWORD_DEFAULT);

		try {
			$stmt = $this->pdo->prepare("INSERT INTO users (user_name, user_password) VALUES (?, ?)");
			return $stmt->execute([$this->userArray['user_name'], $hashedPassword]);

		} catch (Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}

	public function deleteAccount(): bool
	{
		if (!$this->prepareData()) return false;
		if (!$this->validateData()) return false;

		try {
			$stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = ?");
			return $stmt->execute([$this->userArray['user_id']]);

		} catch (Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}
}