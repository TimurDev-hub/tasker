<?php

namespace Templates;

class TemplateModel
{
	private const MIN_TITLE_LEN = 4;
	private const MAX_TITLE_LEN = 32;

	private const MIN_TASK_LEN = 8;
	private const MAX_TASK_LEN = 128;

	private const MIN_NAME_SIZE = 3;
	private const MAX_NAME_SIZE = 12;

	private const MIN_PASS_SIZE = 8;
	private const MAX_PASS_SIZE = 24;

	protected function prepareData(array $data): bool
	{
		if (empty($data)) return false;

		foreach ($data as &$item) {
			$item = strval(htmlspecialchars(trim($item)));
		}

		return true;
	}

	protected function validateData(array $data): bool
	{
		foreach ($data as $key => $item) {
			switch ($key) {
				case 'user_id':
				case 'task_id':
					if (empty($item)) return false;
					elseif (!is_numeric($item)) return false;
					break;
				case 'task_title':
					if (empty($item)) return false;
					elseif (strlen($item) < self::MIN_TITLE_LEN || strlen($item) > self::MAX_TITLE_LEN) return false;
					break;
				case 'task_text':
					if (empty($item)) return false;
					elseif (strlen($item) < self::MIN_TASK_LEN || strlen($item) > self::MAX_TASK_LEN) return false;
					break;
				case 'user_name':
					if (empty($item)) return false;
					elseif (strlen($item) < self::MIN_NAME_SIZE || strlen($item) > self::MAX_NAME_SIZE) return false;
					break;
				case 'user_password':
					if (empty($item)) return false;
					elseif (strlen($item) < self::MIN_PASS_SIZE || strlen($item) > self::MAX_PASS_SIZE) return false;
					break;
				default:
					return false;
			}
		}

		return true;
	}
}