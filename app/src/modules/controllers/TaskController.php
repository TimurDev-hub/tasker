<?php

namespace Controllers;

use Database\Database;
use Models\TaskModel;
use Shared\ErrorLogger;

class TaskContoller
{
	private function checkData(array $requiredFields): bool
	{
		foreach ($requiredFields as $field) {
			if (!isset($_POST[$field])) return false;
		}

		return true;
	}

	// create task
	public function post(): string
	{
		if (!$this->checkData(['user_id', 'task_title', 'task_text'])) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$taskModel = new TaskModel(pdo: $pdo, taskData: $_POST);

			if ($taskModel->createTask()) {
				http_response_code(201);
				return json_encode(['message' => 'Created successfully!']);
			} else {
				http_response_code(400);
				return json_encode(['error' => 'Failed to create task']);
			}

		} catch (\Throwable $exc) {
			ErrorLogger::handleError($exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	// get tasks
	public function get(): string
	{
		if (!$this->checkData(['user_id'])) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$taskModel = new TaskModel(pdo: $pdo, taskData: $_POST);
			$tasks = $taskModel->getTasks();

			if (!$tasks) {
				http_response_code(500);
				return json_encode(['error' => 'Internal Server Error']);
			}

			return json_encode(['tasks' => $tasks]);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	// delete task
	public function delete(): string
	{
		if (!$this->checkData(['task_id'])) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$taskModel = new TaskModel(pdo: $pdo, taskData: $_POST);

			if ($taskModel->deleteTask()) {
				http_response_code(200);
				return json_encode(['message' => 'Deleted successfully!']);
			} else {
				http_response_code(400);
				return json_encode(['error' => 'Failed to delete task']);
			}

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}
}