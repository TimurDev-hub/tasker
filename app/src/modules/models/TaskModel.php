<?php

namespace Models;

use Templates\TemplateModel;
use Utils\ErrorLogger;

final class TaskModel extends TemplateModel
{
	private \PDO $pdo;
	private ?array $taskData;

	public function __construct(\PDO $pdo, ?array $taskData = null)
	{
		$this->pdo = $pdo;
		$this->taskData = $taskData;
	}

	public function createTask(): bool
	{
		if (!$this->taskData || !isset($this->taskData['user_id'], $this->taskData['task_title'], $this->taskData['task_text'])) return false;

		if (!$this->prepareData(data: $this->taskData)) return false;
		if (!$this->validateData(data: $this->taskData)) return false;

		try {
			$stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, task_title, task_text) VALUES(?, ?, ?)");
			return $stmt->execute([$this->taskData['user_id'], $this->taskData['task_title'], $this->taskData['task_text']]);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}

	public function checkTasksQuantity(): bool|int
	{
		if (!$this->taskData || !isset($this->taskData['user_id'])) return false;

		if (!$this->prepareData(data: $this->taskData)) return false;
		if (!$this->validateData(data: $this->taskData)) return false;

		try {
			$stmt = $this->pdo->prepare("SELECT COUNT(task_id) FROM tasks WHERE user_id = ?");
			$stmt->execute([$this->taskData['user_id']]);
			return $stmt->fetchColumn();

		} catch(\Throwable $exc) {
			ErrorLogger::handleError(exc: $exc);
			return false;
		}
	}

	public function getTasks(int $userId): array|false
	{
		if (!$userId) return false;
		if ($userId && !is_integer($userId)) return false;

		try {
			$stmt = $this->pdo->prepare("SELECT task_id, task_title, task_text FROM tasks WHERE user_id = ?");
			$stmt->execute([$userId]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}

	public function deleteTask(int $taskId): bool
	{
		if (!$taskId) return false;
		if ($taskId && !is_integer($taskId)) return false;

		try {
			$stmt = $this->pdo->prepare("DELETE FROM tasks WHERE task_id = ?");
			return $stmt->execute([$taskId]);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}
}