<?php

require_once __DIR__ . "../../../vendor/autoload.php";

$file = new Shared\StringParser(filePath: __DIR__ . '/../../.env');

echo '<pre>';
print_r($file->loadFile());
echo '<br>';
print_r($file->explodedFile($file->loadFile()));
echo '<br>';
print_r($file->getKeys($file->explodedFile($file->loadFile())));
echo '<br>';
print_r($file->getValues($file->explodedFile($file->loadFile())));
echo '</pre>';

//require_once __DIR__ . '../../src/modules/views/index.html';
