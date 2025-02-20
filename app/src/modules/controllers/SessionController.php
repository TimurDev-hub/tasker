<?php

namespace Controllers;

use Database\Database;
use Models\UserModel;
use Shared\ErrorLogger;

class SessionController
{
	private function checkData(array $requiredFields): bool
	{
		foreach ($requiredFields as $field) {
			if (!isset($_POST[$field])) return false;
		}

		return true;
	}

	// login
	public function get(): string
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

			return json_encode(['error' => 'Login successful!']);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	// logout
	public function delete(): string
	{
		session_destroy();
		http_response_code(200);
		return json_encode(['message' => 'Logged out successfully!']);
	}
}