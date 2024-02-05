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
        if (CONFIG['USERAGENT_BAN_ACTIVE'] === 'true' && self::isUserAgentBanned(self::getClientIdentifier($request))) {
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
    public static function banUserAgent(string $userAgent): bool
    {
        $jails = JailLoader::load();
        $jails[$userAgent] = (time() + self::getUserAgentBanDuration());
        return JailLoader::save($jails);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function unbanUserAgent(string $userAgent): bool
    {
        if (is_int(preg_match(self::USERAGENT_MATCH_REGEX, $userAgent))) {
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

    public static function getClientIdentifier(Request $request): string
    {
        return sha1(
            $request->getServer()[CONFIG['IP_ADDRESS_KEY']]
            . '_' . $request->getServer()['HTTP_ACCEPT_LANGUAGE']
            . '_' . $request->getServer()['HTTP_USER_AGENT']
        );
    }
}