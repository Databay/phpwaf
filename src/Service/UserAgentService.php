<?php

namespace App\Service;

use App\Entity\Request;

class UserAgentService
{
    const USERAGENT_MATCH_REGEX = '/^[0-9a-f]{40}$/';

    /**
     * @codeCoverageIgnore
     */
    public static function handleUserAgent(Request $request)
    {
        $clientIdentifier = self::getClientIdentifier($request);
        if (CONFIG['USERAGENT_BAN_ACTIVE'] === 'true' && self::isUserAgentBanned($clientIdentifier)) {
            Logger::log('User-Agent ' . $clientIdentifier . ' tried to access the site but is banned', Logger::DEBUG);
            http_response_code(403);
            exit;
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public static function isUserAgentBanned(string $userAgent): bool
    {
        $jail = JailLoader::load();
        return isset($jail[$userAgent]) && $jail[$userAgent] > time();
    }

    /**
     * @codeCoverageIgnore
     */
    public static function banUserAgent(string $userAgent, bool $log = false): bool
    {
        if (self::isValidUserAgent($userAgent)) {
            $jails = JailLoader::load();
            $banEnd = time() + self::getUserAgentBanDuration();
            $jails[$userAgent] = $banEnd;
            $saved = JailLoader::save($jails);

            if ($saved && $log) {
                Logger::log('User Agent ' . $userAgent . ' is banned until ' . date('Y-m-d H:i:s', $banEnd), Logger::CRITICAL);
            }

            return $saved;
        }

        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function unbanUserAgent(string $userAgent): bool
    {
        if (self::isValidUserAgent($userAgent)) {
            $jails = JailLoader::load();
            unset($jails[$userAgent]);
            return JailLoader::save($jails);
        }

        return false;
    }

    private static function getUserAgentBanDuration(): int
    {
        if (isset(CONFIG['USERAGENT_BAN_DURATION'])) {
            return (CONFIG['USERAGENT_BAN_DURATION'] === 'permanent') ? PHP_INT_MAX : max((int) CONFIG['USERAGENT_BAN_DURATION'], 1);
        }

        return JailService::DEFAULT_BAN_TIME;
    }

    public static function getClientIdentifier(Request $request, bool $hashed = true): string
    {
        $clientIdentifier =
            $request->getServer()[CONFIG['IP_ADDRESS_KEY']]
            . '_' . $request->getServer()['HTTP_ACCEPT_LANGUAGE']
            . '_' . $request->getServer()['HTTP_USER_AGENT']
        ;

        return $hashed ? sha1($clientIdentifier) : $clientIdentifier;
    }

    private static function isValidUserAgent(string $userAgent): bool
    {
        return (int) preg_match(self::USERAGENT_MATCH_REGEX, $userAgent) === 1;
    }
}