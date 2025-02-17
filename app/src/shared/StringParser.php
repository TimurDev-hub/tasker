<?php

namespace Shared;

class StringParser
{
	private string $filePath;

	public function __construct(string $filePath)
	{
		$this->filePath = $filePath;
	}

	public function loadFile(): array
	{
		return file($this->filePath);
	}

	public function explodedFile(array $fileArray): array
	{
		$preparedArray = [];

		foreach ($fileArray as $item) {
			$preparedArray[] = explode('=', $item);
		}

		return $preparedArray;
	}

	public function getKeys(array $data): array
	{
		$keys = [];

		foreach ($data as $groups) {
			foreach ($groups as $items) {
				$keys[] = $items;
				break;
			}
		}

		return $keys;
	}

	public function getValues(array $data): array
	{
		$values = [];

		foreach ($data as $groups) {
			for ($i = 1; $i < count($groups); $i++) {
				$values[] = $groups[$i];
				break;
			}
		}

		return $values;
	}
}