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

	protected function pregCheckData(array $requiredFields, array $data): bool
	{
		foreach ($requiredFields as $field) {
			if (!preg_match('/^[a-zA-Z0-9\\-_=+@#!&?]+$/is', $data[$field])) return false;
		}

		return true;
	}

	protected function checkDataSize(array $data, int $min, int $max): bool
	{
		foreach ($data as $item) {
			if (strlen($item) < $min || strlen($item) > $max) return false;
		}

		return true;
	}
}