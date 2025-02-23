<?php

namespace Controllers;

use Templates\TemplateController;
use Database\Database;
use Models\UserModel;
use Utils\ErrorLogger;

class UserController extends TemplateController
{
	// create account
	public function post(): string
	{
		$userData = $this->getJsonContents();

		if (!$this->checkData(requiredFields: ['user_name', 'user_password'], data: $userData)) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
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

	// delete account
	public function delete(): string
	{
		$userData = $this->getJsonContents();

		if (!$this->checkData(requiredFields: ['user_id'], data: $userData)) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$userModel = new UserModel(pdo: $pdo, userData: $userData);

			if (!$userModel->deleteAccount()) {
				http_response_code(400);
				return json_encode(['error' => 'Failed to delete account']);
			}

			setcookie('user_id', "", time() - 3600);
			setcookie('user_name', "", time() - 3600);

			http_response_code(200);
			return json_encode(['message' => 'Deleted successfully!']);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}
}