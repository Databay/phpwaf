<?php
if ($argc < 3) {
    echo 'Usage: php ' . basename(__FILE__) . ' <path/to/file.txt> <path/to/newFileName.php>' . PHP_EOL; exit(1);
}

array_shift($argv);
list($filePath, $newFilePath) = $argv;

$newFilePath = trim($newFilePath, '/');
$newFullFilePath = __DIR__ . '/../../' . $newFilePath;
$data = <<<END
<?php
return [
END;

file_put_contents($newFullFilePath, $data . PHP_EOL);

foreach (file(__DIR__ . '/../../' . trim($filePath, '/')) as $line) {
    $line = trim($line);
    $line = trim($line, PHP_EOL);
    $line = str_replace(['\\', '\''], ['\\\\', '\\\''], $line);

    if (!isset($values[$line])) {
        $values[$line] = false;
        file_put_contents($newFullFilePath, "\t" . '\'' . $line . '\' => \'' .$line . '\',' . PHP_EOL, FILE_APPEND);
    }
}

file_put_contents($newFullFilePath, '];', FILE_APPEND);

try {
    $array = require_once($newFullFilePath);
} catch (Throwable $e) {
    unlink($newFullFilePath);
    echo 'An error occurred. Please check the files and try again.' . PHP_EOL; exit(1);
}

echo 'Finished' . PHP_EOL;