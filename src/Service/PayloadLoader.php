<?php

namespace App\Service;

use App\Abstracts\AbstractFileLoader;

class PayloadLoader extends AbstractFileLoader
{
    /**
     * @codeCoverageIgnore
     */
    public static function load(string $path): array
    {
        $payload = [];

        // Allows php files to be used to use the benefits of OPCache
        if (substr($path, -4) === '.php' && file_exists($path)) {
            $payload = include($path);

            return is_array($payload) ? array_filter($payload) : [];
        }

        if (file_exists($path) && is_array($fileContents = file($path))) {
            foreach ($fileContents as $value) {
                $value = trim(self::removeComments($value));
                $payload[$value] = $value;
            }
        }

        return array_filter($payload);
    }
}