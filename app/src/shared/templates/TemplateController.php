<?php

namespace Templates;

class TemplateController
{
	protected function checkData(array $requiredFields): bool
	{
		foreach ($requiredFields as $field) {
			if (!isset($_POST[$field]) || empty($_POST[$field])) return false;
		}

		return true;
	}
}