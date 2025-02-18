<?php

namespace Shared;

use Throwable;

class EnvParser
{
	private string $filePath;

	public function __construct(string $filePath)
	{
		$this->filePath = $filePath;
	}

	private function loadFile(): array
	{
		return file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	}

	public function parseFile(): array|false
	{
		$fileData = [];

		try {
			foreach ($this->loadFile() as $line) {
				$linesArray = explode('=', $line, 2);
	
				$key = $linesArray[0];
				$value = $linesArray[1];
	
				$fileData[$key] = $value;
			}

		} catch (Throwable) {
			return false;
		}

		return $fileData;
	}
}