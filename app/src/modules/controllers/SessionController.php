<?php

namespace Controllers;

use Templates\TemplateController;
use Database\Database;
use Models\UserModel;
use Utils\ErrorLogger;

class SessionController extends TemplateController
{
	// login
	public function get(): string
	{
		$sessionData = $this->getJsonContents(url: '/');

		if (!$this->checkData(requiredFields: ['user_name', 'user_password'], data: $sessionData)) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$userModel = new UserModel(pdo: $pdo, userData: $sessionData);
			$user = $userModel->getUserByName();

			if (!$user) {
				http_response_code(401);
				return json_encode(['error' => 'Invalid credentials']);
			}

			if (!password_verify($_POST['user_password'], $user['user_password'])) {
				http_response_code(401);
				return json_encode(['error' => 'Invalid credentials']);
			}

			session_start();

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