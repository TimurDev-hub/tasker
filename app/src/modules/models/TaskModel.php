<?php

namespace Models;

use PDO;
use Shared\ErrorLogger;
use Throwable;

class TaskModel
{
	private const MIN_STR_LEN = 3;
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
		foreach ($this->taskData as $item) {
			if (empty($item)) return false;
			elseif (mb_strlen($item) <= self::MIN_STR_LEN) return false;
		}

		return true;
	}

	public function enterTask(): bool
	{
		if (!$this->prepareData()) return false;
		if (!$this->validateData()) return false;

		try {
			$stmt = $this->pdo->prepare("INSERT INTO tasks (task_title, task_text) VALUES(?, ?)");
			return $stmt->execute([$this->taskData['user_id'], $this->taskData['title'], $this->taskData['text']]);

		} catch (Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}

	public function loadTask(): array|false
	{
		try {
			$stmt = $this->pdo->prepare("SELECT * FROM tasks");
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		} catch (Throwable $exc) {
			ErrorLogger::handleError($exc);
			return false;
		}
	}

	public function deleteTask(): bool
	{
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