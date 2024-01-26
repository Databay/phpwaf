<?php

namespace App\Service;

use App\Abstracts\FileLoader;

class PayloadLoader extends FileLoader
{
    private static PayloadLoader $payloadLoader;

    public static function getInstance(): self
    {
        if (self::$payloadLoader === null) {
            self::$payloadLoader = new self();
        }

        return self::$payloadLoader;
    }

    public function loadPayload(string $path): array
    {
        $payload = [];

        // Allows php files to be used to use the benefits of OPCache
        if (substr($path, -4) === '.php') {
            $payload = include_once($path);

            return is_array($payload) ? array_filter($payload) : [];
        }

        if (is_array($fileContents = file($path))) {
            foreach ($fileContents as $value) {
                $value = trim(self::removeComments($value));
                $payload[$value] = $value;
            }
        }

        return array_filter($payload);
    }
}