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
		if (!$this->checkData(['user_name', 'user_password'])) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$userModel = new UserModel(pdo: $pdo, userData: $_POST);

			if ($userModel->createAccount()) {
				http_response_code(201);
				return json_encode(['message' => 'Created successfully!']);
			} else {
				http_response_code(400);
				return json_encode(['error' => 'Failed to create account']);
			}

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	// delete account
	public function delete(): string
	{
		if (!$this->checkData(['user_id'])) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$userModel = new UserModel(pdo: $pdo, userData: $_POST);

			if ($userModel->deleteAccount()) {
				session_destroy();
				http_response_code(200);
				return json_encode(['message' => 'Deleted successfully!']);
			} else {
				http_response_code(400);
				return json_encode(['error' => 'Failed to delete account']);
			}

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}
}