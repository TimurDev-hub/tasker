<?php

namespace Templates;

class TemplateController
{
	protected function getJsonContents(): array
	{
		$json = file_get_contents('php://input');
		return json_decode($json, true);
	}

	protected function checkData(array $requiredFields, array $data): bool
	{
		foreach ($requiredFields as $field) {
			if (empty(trim($data[$field])) || !isset($data[$field])) return false;
		}

		return true;
	}
}