<?php

namespace Models;

use PDO;
use Throwable;

class UserModel
{
	private const MIN_NAME_SIZE = 3;
	private const MAX_NAME_SIZE = 9;
	private const MIN_PASS_SIZE = 6;
	private const MAX_PASS_SIZE = 12;

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
		foreach ($this->userArray as $item) {
			switch ($item) {
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

	public function createAccount(): bool
	{
		if ($this->prepareData()) return false;
		if ($this->validateData()) return false;

		$hashedPassword = password_hash($this->userArray['user_password'], PASSWORD_DEFAULT);

		try {
			$stmt = $this->pdo->prepare("INSERT INTO users (user_name, user_password) VALUES (?, ?)");
			return $stmt->execute([$this->userArray['user_name'], $hashedPassword]);

		} catch (Throwable) {
			return false;
		}
	}

	public function deleteAccount(): bool
	{
		if (empty($this->userArray['user_id']) || !is_numeric($this->userArray['user_id'])) return false;

		try {
			$stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = ?");
			return $stmt->execute([$this->userArray['user_id']]);

		} catch (Throwable) {
			return false;
		}
	}

	public function checkAccount(): bool
	{
		if (!$this->prepareData()) return false;
		if (!$this->validateData()) return false;

		try {
			$stmt = $this->pdo->prepare("SELECT 1 FROM users WHERE user_name = ? OR user_password = ? LIMIT 1");
			$stmt->execute([$this->userArray['user_name'], $this->userArray['user_password']]);
			return $stmt->fetchColumn() > 0;

		} catch (Throwable) {
			return false;
		}
	}
}