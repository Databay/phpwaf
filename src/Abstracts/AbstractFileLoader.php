<?php

namespace App\Abstracts;

use App\Service\Logger;

abstract class AbstractFileLoader
{
    /**
     * @codeCoverageIgnore
     */
    public static function checkOPcache()
    {
        if (CONFIG['OPCACHE_CHECK'] !== 'true') {
            return;
        }

        // Check if OPcache is installed and enabled
        if (!extension_loaded('Zend OPcache')) {
            Logger::log('OPcache is not installed', Logger::DEBUG);
            return;
        }

        if (!opcache_get_status()['opcache_enabled']) {
            Logger::log('OPcache is not enabled', Logger::DEBUG);
        }
    }

    protected static function removeComments(string $value): string
    {
        return (strpos($value, '#') === false) ? $value : strstr($value, '#', true);
    }
}