<?php

namespace Controllers;

use Database\Database;
use Models\UserModel;
use Shared\ErrorLogger;
use Throwable;

class UserController
{
	public function createAccount(): string
	{
		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$userModel = new UserModel(pdo: $pdo, userArray: $_POST);

			if ($userModel->createAccount()) {
				return json_encode(['message' => 'Created successfully!']);
			} else {
				return json_encode(['error' => 'Failed to create account']);
			}

		} catch (Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	public function deleteAccount(): string
	{
		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$userModel = new UserModel(pdo: $pdo, userArray: $_POST);

			if ($userModel->deleteAccount()) {
				return json_encode(['message' => 'Deleted successfully!']);
			} else {
				return json_encode(['error' => 'Failed to delete account']);
			}

		} catch (Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}
}