<?php

namespace App\Service;

class ConfigLoader
{
    public static function loadConfig(): array
    {
        $rootPath = __DIR__ . '/../../';

        $envPath = $rootPath . '.env'; // Priority 4
        $config = self::loadConfigFile($envPath);

        $envLocalPath = $rootPath . '.env.local'; // Priority 3
        $config = array_merge($config, self::loadConfigFile($envLocalPath));

        if (isset($_SERVER['HTTP_HOST'])) {
            $envHostnamePath = $rootPath . '.env.' . $_SERVER['HTTP_HOST']; // Priority 2
            $config = array_merge($config, self::loadConfigFile($envHostnamePath));
        }

        if (defined('WAF_ENV_FILE') && is_string(WAF_ENV_FILE)) {
            $envCustomPath = $rootPath . '.env.' . str_replace(['.env.', '.env'], ['', ''], WAF_ENV_FILE); // Priority 1
            $config = array_merge($config, self::loadConfigFile($envCustomPath));
        }

        return $config;
    }

    private static function loadConfigFile(string $path): array
    {
        $config = [];

        if (file_exists($path)) {
            $fileContents = file($path);

            foreach ($fileContents as $value) {
                if (strpos($value, '#') !== false) {
                    $value = strstr($value, '#', true); // Remove comments (everything after #)
                }

                $explodedKeyValue = explode('=', trim($value));
                if (count($explodedKeyValue) === 2) {
                    $config[$explodedKeyValue[0]] = $explodedKeyValue[1];
                }
            }
        }

        return $config;
    }
}