<?php

namespace Controllers;

use Templates\TemplateController;
use Database\Database;
use Models\UserModel;
use Utils\ErrorLogger;

class UserController extends TemplateController
{
	public function registerUser(mixed $empty = null): string
	{
		$userData = $this->getJsonContents();

		if (!$this->checkData(requiredFields: ['user_name', 'user_password'], data: $userData)) {
			http_response_code(400);
			return json_encode(['error' => 'Missing required fields']);
		}

		if (!$this->checkDataSize(data: $userData, min: 3, max: 16)) {
			http_response_code(400);
			return json_encode(['error' => 'Uncorrect data size']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$userModel = new UserModel(pdo: $pdo, userData: $userData);

			if (!$userModel->createAccount()) {
				http_response_code(400);
				return json_encode(['error' => 'Failed to create account']);
			}

			http_response_code(201);
			return json_encode(['message' => 'Created successfully!']);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	public function deleteUser(int $userId): string
	{
		if ($userId && !is_integer($userId)) return json_encode(['script' => false]);

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$userModel = new UserModel(pdo: $pdo);

			if (!$userModel->deleteAccount(userId: $userId)) {
				http_response_code(400);
				return json_encode(['error' => 'Failed to delete account']);
			}

			$cookie_domain = $_SERVER['HTTP_HOST'];

			setcookie('user_id', "", time() - 3600, '/', $cookie_domain);
			setcookie('user_name', "", time() - 3600, '/', $cookie_domain);

			http_response_code(200);
			return json_encode(['script' => true]);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['script' => false]);
		}
	}
}