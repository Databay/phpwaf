<?php
if ($argc < 3) {
    echo 'Usage: php ' . basename(__FILE__) . ' <path/to/file.txt> <path/to/newFileName.txt>' . PHP_EOL; exit(1);
}

array_shift($argv);
list($filePath, $newFilePath) = $argv;

$newFilePath = trim($newFilePath, '/');
$newFullFilePath = __DIR__ . '/../../' . $newFilePath;

foreach (file(__DIR__ . '/../../' . trim($filePath, '/')) as $line) {
    $line = trim($line);
    $line = strstr($line, '/', false);

    if (!isset($values[$line])) {
        $values[$line] = false;
        file_put_contents($newFullFilePath, $line . PHP_EOL, FILE_APPEND);
    }
}

echo 'Finished' . PHP_EOL;