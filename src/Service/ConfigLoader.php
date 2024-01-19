<?php

namespace App\Service;

class ConfigLoader
{
    public static function loadConfig(): array
    {
        $envPath = __DIR__ . '/../../.env'; // prio 4
        $envLocalPath = __DIR__ . '/../../.env.local'; // prio 3
		if(defined("waf_env")){
			$envLocalPath = __DIR__ . '/../../.env.'.waf_env; // prio 1
		}elseif (file_exists(__DIR__ . '/../../.env.'.$_SERVER["HTTP_HOST"])){
			$envLocalPath = __DIR__ . '/../../.env.'.$_SERVER["HTTP_HOST"]; // prio 2
		}

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