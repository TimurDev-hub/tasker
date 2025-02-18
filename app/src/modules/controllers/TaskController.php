<?php

namespace Controllers;

use Database\Database;
use Models\TaskModel;
use Shared\ErrorLogger;
use Throwable;

class TaskContoller
{
	public function createTask(): string
	{
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

		} catch (Throwable $exc) {
			ErrorLogger::handleError($exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	public function loadTasks(): string
	{
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

		} catch (Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	public function deleteTask(): string
	{
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

		} catch (Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}
}