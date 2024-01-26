<?php

namespace App\Service;

use App\Abstracts\FileLoader;

class ConfigLoader extends FileLoader
{
    private static $configLoader;

    private function __construct() {/* Singleton */}

    public static function getInstance(): self
    {
        if (self::$configLoader === null) {
            self::$configLoader = new self();
        }

        return self::$configLoader;
    }

    public function loadConfig(): array
    {
        $rootPath = __DIR__ . '/../../';
        $configDirectory = $rootPath . 'config/';

        $envPath = $rootPath . 'default.env'; // Priority 4
        $config = $this->loadConfigFile($envPath);

        $envLocalPath = $configDirectory . 'global.env'; // Priority 3
        $config = array_merge($config, $this->loadConfigFile($envLocalPath));

        if (isset($_SERVER['HTTP_HOST'])) {
            $envHostnamePath = $configDirectory . $_SERVER['HTTP_HOST'] . '.env'; // Priority 2
            $config = array_merge($config, $this->loadConfigFile($envHostnamePath));
        }

        if (defined('WAF_ENV_FILE') && is_string(WAF_ENV_FILE)) {
            $envCustomPath = $configDirectory . str_replace(['.env.', '.env'], ['', ''], WAF_ENV_FILE) . '.env'; // Priority 1
            $config = array_merge($config, $this->loadConfigFile($envCustomPath));
        }

        return $config;
    }

    private function loadConfigFile(string $path): array
    {
        $config = [];

        if (is_array($fileContents = file($path))) {
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