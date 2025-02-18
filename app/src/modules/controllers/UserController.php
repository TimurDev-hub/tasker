<?php

namespace Controllers;

use Database\Database;
use Models\UserModel;
use Shared\ErrorLogger;
use Throwable;

class UserController
{
	private function checkData(array $requiredFields): bool
	{
		foreach ($requiredFields as $field) {
			if (!isset($_POST[$field])) return false;
		}

		return true;
	}

	public function createAccount(): string
	{
		if (!$this->checkData(['user_name', 'user_password'])) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$userModel = new UserModel(pdo: $pdo, userArray: $_POST);

			if ($userModel->createAccount()) {
				http_response_code(201);
				return json_encode(['message' => 'Created successfully!']);
			} else {
				http_response_code(400);
				return json_encode(['error' => 'Failed to create account']);
			}

		} catch (Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	public function deleteAccount(): string
	{
		if (!$this->checkData(['user_id'])) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$userModel = new UserModel(pdo: $pdo, userArray: $_POST);

			if ($userModel->deleteAccount()) {
				http_response_code(200);
				return json_encode(['message' => 'Deleted successfully!']);
			} else {
				http_response_code(400);
				return json_encode(['error' => 'Failed to delete account']);
			}

		} catch (Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	public function login()
	{
		if (!$this->checkData(['user_name', 'user_password'])) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$userModel = new UserModel(pdo: $pdo, userArray: $_POST);
			$user = $userModel->getUserByName();

			if (!$user) {
				http_response_code(401);
				return json_encode(['error' => 'Invalid credentials']);
			}

			if (!password_verify($_POST['user_password'], $user['user_password'])) {
				http_response_code(401);
				return json_encode(['error' => 'Invalid credentials']);
			}

			session_regenerate_id();

			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['user_name'] = $user['user_name'];

			return json_encode(['message' => 'Login successful!']);

		} catch (Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}
}