<?php

namespace Utils;

use Throwable;

class ErrorLogger
{
	private const LOCAL_LOG_FILE_PATH = '../../core/logs/errors.log';

	private function getPrefix(): string
	{
		return 'PHP FATAL ERROR: ' . date('Y-m-d H:i:s') . ': ';
	}

	private function getInfo(Throwable $exc): string
	{
		return 'MESSAGE => ' . $exc->getMessage() . '; FILE=> ' . $exc->getFile() . '; LINE=> ' . $exc->getLine();
	}

	public static function handleError(Throwable $exc): void
	{
		$errorPrefix = self::getPrefix();
		$errorInfo = self::getInfo(exc: $exc);

		$errorMessage = $errorPrefix . $errorInfo . PHP_EOL;

		error_log($errorMessage, 3, self::LOCAL_LOG_FILE_PATH);
	}
}