<?php

namespace Controllers;

use Templates\TemplateController;
use Database\Database;
use Models\UserModel;
use Utils\ErrorLogger;

final class AuthenticationController extends TemplateController
{
	public function login(mixed $empty = null): string
	{
		$sessionData = $this->getJsonContents();

		if (!$this->checkData(requiredFields: ['user_name', 'user_password'], data: $sessionData)) {
			http_response_code(400);
			return json_encode(['error' => 'Missing required fields']);
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

			if (!password_verify($sessionData['user_password'], $user['user_password'])) {
				http_response_code(401);
				return json_encode(['error' => 'Invalid username or password']);
			}

			$cookie_domain = $_SERVER['HTTP_HOST'];

			setcookie('user_id', $user['user_id'], time() + 3600, '/', $cookie_domain);
			setcookie('user_name', $user['user_name'], time() + 3600, '/', $cookie_domain);

			return json_encode(['script' => true]);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	public function logout(mixed $empty = null): string
	{
		$cookie_domain = $_SERVER['HTTP_HOST'];

		setcookie('user_id', "", time() - 3600, '/', $cookie_domain);
		setcookie('user_name', "", time() - 3600, '/', $cookie_domain);

		http_response_code(200);
		return json_encode(['script' => true]);
	}
}