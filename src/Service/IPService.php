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
        $ip = self::getIP($request);
        if (CONFIG['IP_BAN_ACTIVE'] === 'true' && self::isIPBanned($ip)) {
            Logger::log('IP ' . $ip . ' tried to access the site but is banned', Logger::DEBUG);
            http_response_code(403);
            exit;
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public static function isIPBanned(string $ip): bool
    {
        $jails = JailLoader::load();
        return isset($jails[$ip]) && $jails[$ip] > time();
    }

    /**
     * @codeCoverageIgnore
     */
    public static function banIP(string $ip, bool $log = false): bool
    {
        if (self::isValidIP($ip)) {
            $jails = JailLoader::load();
            $banEnd = time() + self::getIPBanDuration();
            $jails[$ip] = $banEnd;
            $saved = JailLoader::save($jails);

            if ($saved && $log) {
                Logger::log('IP ' . $ip . ' is banned until ' . date('Y-m-d H:i:s', $banEnd), Logger::CRITICAL);
            }

            return $saved;
        }

        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function unbanIP(string $ip): bool
    {
        if (self::isValidIP($ip)) {
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

    public static function getIP(Request $request): string
    {
        return $request->getServer()[CONFIG['IP_ADDRESS_KEY']];
    }

    private static function isValidIP(string $ip): bool
    {
        return (int) preg_match(self::IP_MATCH_REGEX, $ip) === 1;
    }
}