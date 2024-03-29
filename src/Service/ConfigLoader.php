<?php

namespace App\Service;

use App\Abstracts\AbstractFileLoader;

class ConfigLoader extends AbstractFileLoader
{
    /**
     * @codeCoverageIgnore
     */
    public static function load(): array
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

    /**
     * @codeCoverageIgnore
     */
    private static function loadConfigFile(string $path): array
    {
        $config = [];

        if (file_exists($path) && is_array($fileContents = file($path))) {
            foreach ($fileContents as $value) {
                $value = self::removeComments($value);

                $explodedKeyValue = explode('=', trim($value));
                if (count($explodedKeyValue) === 2) {
                    $config[trim($explodedKeyValue[0])] = trim($explodedKeyValue[1]);
                }
            }
        }

        return $config;
    }
}