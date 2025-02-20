<?php

namespace Models;

use Templates\TemplateModel;
use Utils\ErrorLogger;

class TaskModel extends TemplateModel
{
	private \PDO $pdo;
	private array $taskData;

	public function __construct(\PDO $pdo, array $taskData)
	{
		$this->pdo = $pdo;
		$this->taskData = $taskData;
	}

	public function createTask(): bool
	{
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

	public function getTasks(): array|false
	{
		if (!$this->prepareData(data: $this->taskData)) return false;
		if (!$this->validateData(data: $this->taskData)) return false;

		try {
			$stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
			$stmt->execute([$this->taskData['user_id']]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}

	public function deleteTask(): bool
	{
		if (!$this->prepareData(data: $this->taskData)) return false;
		if (!$this->validateData(data: $this->taskData)) return false;

		try {
			$stmt = $this->pdo->prepare("DELETE FROM tasks WHERE task_id = ?");
			return $stmt->execute([$this->taskData['task_id']]);

		} catch (\Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}
}