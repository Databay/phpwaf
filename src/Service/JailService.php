<?php

namespace App\Service;

class JailService
{
    const DEFAULT_BAN_TIME = 60; // seconds

    public static function pruneJail(): bool
    {
        $jails = JailLoader::load();

        foreach ($jails as $key => $banTime) {
            if ($banTime < time()) {
                unset($jails[$key]);
            }
        }

        return JailLoader::save($jails);
    }
}