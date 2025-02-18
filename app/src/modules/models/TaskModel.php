<?php

namespace Models;

use PDO;
use Shared\ErrorLogger;
use Throwable;

class TaskModel
{
	private const MIN_STR_LEN = 4;
	private const MAX_TITLE_LEN = 32;
	private const MAX_TASK_LEN = 128;

	private PDO $pdo;
	private array $taskData;

	public function __construct(PDO $pdo, array $taskData)
	{
		$this->pdo = $pdo;
		$this->taskData = $taskData;
	}

	private function prepareData(): bool
	{
		if (empty($this->taskData)) return false;

		foreach ($this->taskData as &$item) {
			$item = strval(htmlspecialchars(trim($item)));
		}

		return true;
	}

	private function validateData(): bool
	{
		foreach ($this->taskData as $key => $item) {
			switch ($key) {
				case 'task_title':
					if (empty($item)) return false;
					elseif (strlen($item) < self::MIN_STR_LEN || strlen($item) > self::MAX_TITLE_LEN) return false;
					break;
				case 'task_text':
					if (empty($item)) return false;
					elseif (strlen($item) < self::MIN_STR_LEN || strlen($item) > self::MAX_TASK_LEN) return false;
					break;
			}
		}

		return true;
	}

	public function createTask(): bool
	{
		if (!$this->prepareData()) return false;
		if (!$this->validateData()) return false;

		try {
			$stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, task_title, task_text) VALUES(?, ?, ?)");
			return $stmt->execute([$this->taskData['user_id'], $this->taskData['task_title'], $this->taskData['task_text']]);

		} catch (Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}

	public function getTasks(): array|false
	{
		if (!$this->prepareData()) return false;

		if (empty($this->taskData['user_id']) || !is_numeric($this->taskData['user_id'])) return false;

		try {
			$stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
			$stmt->execute([$this->taskData['user_id']]);
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		} catch (Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}

	public function deleteTask(): bool
	{
		if (!$this->prepareData()) return false;

		if (empty($this->taskData['task_id']) || !is_numeric($this->taskData['task_id'])) return false;

		try {
			$stmt = $this->pdo->prepare("DELETE FROM tasks WHERE task_id = ?");
			return $stmt->execute([$this->taskData['task_id']]);

		} catch (Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}
}