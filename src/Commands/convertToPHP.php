<?php
if ($argc < 3) {
    echo 'Usage: php ' . basename(__FILE__) . ' <relative/path/to/file.txt> <relative/path/to/newFileName.php>' . PHP_EOL; exit(1);
}

array_shift($argv);
list($filePath, $newFilePath) = $argv;

$data = <<<END
<?php
return [
END;

file_put_contents(__DIR__ . '/' . $newFilePath, $data . PHP_EOL);

foreach (file(__DIR__ . '/' . $filePath) as $line) {
    $line = trim($line);
    $line = trim($line, PHP_EOL);
    $line = str_replace(['\\', '\''], ['\\\\', '\\\''], $line);

    if (!isset($values[$line])) {
        $values[$line] = false;
        file_put_contents(__DIR__ . '/' . $newFilePath, "\t" . '\'' . $line . '\' => \'' .$line . '\',' . PHP_EOL, FILE_APPEND);
    }
}

file_put_contents(__DIR__ . '/' . $newFilePath, '];', FILE_APPEND);

try {
    $array = require_once(__DIR__ . '/' .$newFilePath);
} catch (Throwable $e) {
    unlink($newFilePath);
    echo 'An error occurred. Please check the files and try again.' . PHP_EOL; exit(1);
}

echo 'Finished' . PHP_EOL;