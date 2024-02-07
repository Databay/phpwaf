<?php

namespace App\Service;

use App\Entity\Request;

class JailService
{
    const DEFAULT_BAN_TIME = 60; // seconds

    /**
     * @codeCoverageIgnore
     */
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

    /**
     * @codeCoverageIgnore
     */
    public static function handleBanning(Request $request)
    {
        if (CONFIG['USERAGENT_BAN_ACTIVE'] === 'true') {
            UserAgentService::banUserAgent(UserAgentService::getClientIdentifier($request), true);
            return;
        }

        if (CONFIG['IP_BAN_ACTIVE'] === 'true') {
            IPService::banIP(IPService::getIP($request), true);
        }
    }
}