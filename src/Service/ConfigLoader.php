<?php

namespace App\Service;

use App\Abstracts\FileLoader;

class ConfigLoader extends FileLoader
{
    public static function loadConfig(): array
    {
        $rootPath = __DIR__ . '/../../';
        $configDirectory = $rootPath . 'config/';

        $envPath = $rootPath . 'default.env'; // Priority 4
        $config = self::loadConfigFile($envPath);

        $envLocalPath = $configDirectory . 'global.env'; // Priority 3
        $config = array_merge($config, self::loadConfigFile($envLocalPath));

        if (isset($_SERVER['HTTP_HOST'])) {
            $envHostnamePath = $configDirectory . $_SERVER['HTTP_HOST'] . '.env'; // Priority 2
            $config = array_merge($config, self::loadConfigFile($envHostnamePath));
        }

        if (defined('WAF_ENV_FILE') && is_string(WAF_ENV_FILE)) {
            $envCustomPath = $configDirectory . str_replace(['.env.', '.env'], ['', ''], WAF_ENV_FILE) . '.env'; // Priority 1
            $config = array_merge($config, self::loadConfigFile($envCustomPath));
        }

        return $config;
    }

    private static function loadConfigFile(string $path): array
    {
        $config = [];

        if (is_array($fileContents = file($path))) {
            foreach ($fileContents as $value) {
                $value = self::removeComments($value);

                $explodedKeyValue = explode('=', trim($value));
                if (count($explodedKeyValue) === 2) {
                    $config[$explodedKeyValue[0]] = $explodedKeyValue[1];
                }
            }
        }

        return $config;
    }
}