<?php

namespace App\Service;

use App\Abstracts\AbstractFileLoader;

class PayloadLoader extends AbstractFileLoader
{
    public static function loadPayload(string $path): array
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