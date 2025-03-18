<?php

namespace Controllers;

use Templates\TemplateController;
use Database\Database;
use Models\TaskModel;
use Utils\ErrorLogger;

class TaskController extends TemplateController
{
	public function createTask(mixed $empty = null): string
	{
		$taskData = $this->getJsonContents();

		if (!$this->checkData(requiredFields: ['user_id', 'task_title', 'task_text'], data: $taskData)) {
			http_response_code(400);
			return json_encode(['error' => 'Missing required fields']);
		}

		if (!$this->checkDataSize(data: [$taskData['task_title'], $taskData['task_text']], min: 4, max: 64)) {
			http_response_code(400);
			return json_encode(['error' => 'Uncorrect data size']);
		}

		if (!$this->pregCheckData(requiredFields: ['user_id', 'task_title', 'task_text'], data: $taskData)) {
			http_response_code(400);
			return json_encode(['error' => 'Uncorrect data type']);
		}

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$taskModel = new TaskModel(pdo: $pdo, taskData: $taskData);

			if (!$taskModel->checkTasksQuantity() >= 5) {
				http_response_code(400);
				return json_encode([
					'error' => 'Too much tasks!',
					'script' => true
				]);
			}

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

	public function getTasks(int $userId): string
	{
		if ($userId && !is_integer($userId)) return json_encode(['script' => false]);

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$taskModel = new TaskModel(pdo: $pdo);
			$tasks = $taskModel->getTasks(userId: $userId);

			if ($tasks === false) {
				http_response_code(500);
				return json_encode(['error' => 'Internal Server Error']);
			}

			return json_encode([
				'tasks' => $tasks,
				'script' => true
			]);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}

	public function deleteTask(int $taskId): string
	{
		if ($taskId && !is_integer($taskId)) return json_encode(['script' => false]);

		try {
			$db = new Database();
			$pdo = $db->returnPdo();

			$taskModel = new TaskModel(pdo: $pdo);

			if (!$taskModel->deleteTask(taskId: $taskId)) {
				http_response_code(400);
				return json_encode(['error' => 'Failed to delete task']);
			}

			http_response_code(200);
			return json_encode([
				'message' => 'Deleted successfully!',
				'script' => true
			]);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			http_response_code(500);
			return json_encode(['error' => 'Internal Server Error']);
		}
	}
}