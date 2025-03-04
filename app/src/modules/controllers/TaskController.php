<?php

namespace Controllers;

use Templates\TemplateController;
use Database\Database;
use Models\TaskModel;
use Utils\ErrorLogger;

class TaskController extends TemplateController
{
	public function createTask(?string $id = null): string
	{
		$taskData = $this->getJsonContents();

		if (!$this->checkData(requiredFields: ['user_id', 'task_title', 'task_text'], data: $taskData)) {
			http_response_code(400);
			return json_encode(['error' => 'Missing required fields']);
		}

		if (!$this->checkDataSize(data: [$taskData['task_title'], $taskData['task_text']], min: 4, max: 128)) {
			http_response_code(400);
			return json_encode(['error' => 'Uncorrect data size']);
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
			return json_encode([
				'message' => 'Created successfully!',
				'script' => true
			]);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError($exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	public function getTasks(?string $id = null): string
	{
		$taskData = $this->getJsonContents();

		if (!$this->checkData(requiredFields: ['user_id'], data: $taskData)) {
			http_response_code(400);
			return json_encode(['error' => 'Missing required fields']);
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

	public function deleteTask(?string $id = null): string
	{
		$taskData = $this->getJsonContents();

		if (!$this->checkData(requiredFields: ['task_id'], data: $taskData)) {
			http_response_code(400);
			return json_encode(['error' => 'Missing required fields']);
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