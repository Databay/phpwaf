<?php

namespace App\Service;

class JailService
{
    const IP_MATCH_REGEX = '/^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/';

    const DEFAULT_BAN_TIME = 60; // seconds

    public static function isIPBanned(string $ip): bool
    {
        $jail = JailLoader::load();
        return isset($jail[$ip]) && $jail[$ip] > time();
    }

    public static function banIP(string $ip): bool
    {
        if (is_int(preg_match(self::IP_MATCH_REGEX, $ip))) {
            $jails = JailLoader::load();
            $jails[$ip] = (time() + self::getBanDuration());
            return JailLoader::save($jails);
        }

        return false;
    }

    public static function unbanIP(string $ip): bool
    {
        if (is_int(preg_match(self::IP_MATCH_REGEX, $ip))) {
            $jails = JailLoader::load();
            unset($jails[$ip]);
            return JailLoader::save($jails);
        }

        return false;
    }

    public static function pruneJail(): bool
    {
        $jails = JailLoader::load();

        foreach ($jails as $ip => $banTime) {
            if ($banTime < time()) {
                unset($jails[$ip]);
            }
        }

        return JailLoader::save($jails);
    }

    private static function getBanDuration(): int
    {
        if (isset(CONFIG['BAN_DURATION'])) {
            return (CONFIG['BAN_DURATION'] === 'permanent') ? PHP_INT_MAX : max((int) CONFIG['BAN_DURATION'], 1);
        }

        return self::DEFAULT_BAN_TIME;
    }
}