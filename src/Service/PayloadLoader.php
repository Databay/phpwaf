<?php

namespace App\Service;

use App\Abstracts\FileLoader;

class PayloadLoader extends FileLoader
{
    public static function loadPayload(string $path): array
    {
        $payload = [];

        if (is_array($fileContents = file($path))) {
            foreach ($fileContents as $value) {
                $payload[] = trim(self::removeComments($value));
            }
        }

        return array_filter($payload);
    }
}