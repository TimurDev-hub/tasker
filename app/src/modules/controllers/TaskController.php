<?php

namespace Controllers;

use Templates\TemplateController;
use Database\Database;
use Models\TaskModel;
use Utils\ErrorLogger;

class TaskContoller extends TemplateController
{
	// create task
	public function post(): string
	{
		$taskData = $this->getJsonContents();

		if (!$this->checkData(requiredFields: ['user_id', 'task_title', 'task_text'], data: $taskData)) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$taskModel = new TaskModel(pdo: $pdo, taskData: $taskData);

			if (!$taskModel->createTask()) {
				http_response_code(400);
				return json_encode(['error' => 'Failed to create task']);
			}

			http_response_code(201);
			return json_encode(['message' => 'Created successfully!']);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError($exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	// get tasks
	public function get(): string
	{
		$taskData = $this->getJsonContents();

		if (!$this->checkData(requiredFields: ['user_id'], data: $taskData)) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$taskModel = new TaskModel(pdo: $pdo, taskData: $taskData);
			$tasks = $taskModel->getTasks();

			if ($tasks === false) {
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
		$taskData = $this->getJsonContents();

		if (!$this->checkData(requiredFields: ['task_id'], data: $taskData)) {
			http_response_code(400);
			return json_encode(['Missing required fields']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$taskModel = new TaskModel(pdo: $pdo, taskData: $taskData);

			if (!$taskModel->deleteTask()) {
				http_response_code(400);
				return json_encode(['error' => 'Failed to delete task']);
			}

			http_response_code(200);
			return json_encode(['message' => 'Deleted successfully!']);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}
}