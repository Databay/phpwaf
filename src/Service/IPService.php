<?php

namespace App\Service;

use App\Entity\Request;

class IPService
{
    const IP_MATCH_REGEX = '/^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/';

    /**
     * @codeCoverageIgnore
     */
    public static function handleIP(Request $request)
    {
        if (CONFIG['IP_BAN_ACTIVE'] === 'true' && self::isIPBanned($request->getServer()['REMOTE_ADDR'])) {
            http_response_code(403);
            exit;
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public static function isIPBanned(string $ip): bool
    {
        $jail = JailLoader::load();
        return isset($jail[$ip]) && $jail[$ip] > time();
    }

    /**
     * @codeCoverageIgnore
     */
    public static function banIP(string $ip): bool
    {
        if (is_int(preg_match(self::IP_MATCH_REGEX, $ip))) {
            $jails = JailLoader::load();
            $jails[$ip] = (time() + self::getIPBanDuration());
            return JailLoader::save($jails);
        }

        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function unbanIP(string $ip): bool
    {
        if (is_int(preg_match(self::IP_MATCH_REGEX, $ip))) {
            $jails = JailLoader::load();
            unset($jails[$ip]);
            return JailLoader::save($jails);
        }

        return false;
    }

    private static function getIPBanDuration(): int
    {
        if (isset(CONFIG['IP_BAN_DURATION'])) {
            return (CONFIG['IP_BAN_DURATION'] === 'permanent') ? PHP_INT_MAX : max((int) CONFIG['IP_BAN_DURATION'], 1);
        }

        return JailService::DEFAULT_BAN_TIME;
    }
}