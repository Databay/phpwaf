<?php

namespace App\Service;

use App\Abstracts\AbstractFileLoader;

class JailLoader extends AbstractFileLoader
{
    const DEFAULT_JAILFILE_PATH = __DIR__ . '/../../jail.json';

    public static function load(): array
    {
        $jails = [];

        if ($jailsJson = file_get_contents(self::DEFAULT_JAILFILE_PATH)) {
            $jails = json_decode($jailsJson, true) ?? [];
        }

        return $jails;
    }

    public static function save(array $jails): bool
    {
        return file_put_contents(self::DEFAULT_JAILFILE_PATH, json_encode($jails, JSON_PRETTY_PRINT));
    }
}