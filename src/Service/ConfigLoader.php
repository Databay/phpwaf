<?php

namespace App\Service;

class ConfigLoader
{
    public static function loadConfig(): array
    {
        $envPath = __DIR__ . '/../../.env';
        $envLocalPath = __DIR__ . '/../../.env.local';

        if (file_exists($envPath)) {
            $fileContents = file($envPath);

            foreach ($fileContents as $value) {
                $exploded = explode('=', trim($value));
                if (count($exploded) === 2) {
                    $config[$exploded[0]] = $exploded[1];
                }
            }
        }

        if (file_exists($envLocalPath)) {
            $fileContents = file($envLocalPath);

            foreach ($fileContents as $value) {
                $exploded = explode('=', trim($value));
                if (count($exploded) === 2) {
                    $config[$exploded[0]] = $exploded[1];
                }
            }
        }

        return $config ?? [];
    }
}