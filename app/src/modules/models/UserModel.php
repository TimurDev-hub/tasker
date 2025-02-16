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
				case 'username':
					if (empty($item)) return false;
					elseif (mb_strlen($item) < self::MIN_NAME_SIZE || mb_strlen($item) > self::MAX_NAME_SIZE) return false;
					break;
				case 'password':
					if (empty($item)) return false;
					elseif (mb_strlen($item) < self::MIN_PASS_SIZE || mb_strlen($item) > self::MAX_PASS_SIZE) return false;
					break;
			}
		}

		return true;
	}
}