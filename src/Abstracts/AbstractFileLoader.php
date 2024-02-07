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
        $extensionLoaded = extension_loaded('Zend OPcache');
    
        if (!$extensionLoaded) {
            Logger::log('OPcache is not installed', Logger::INFO);
            return;
        }

    $extensionEnabled = opcache_get_status()['opcache_enabled'];

        if (!$extensionLoaded && !$extensionEnabled) {
            Logger::log('OPcache is not installed and not enabled', Logger::INFO);
            return;
        }

        if (!$extensionEnabled) {
            Logger::log('OPcache is not enabled', Logger::INFO);
            return;
        }
    }

    protected static function removeComments(string $value): string
    {
        return (strpos($value, '#') === false) ? $value : strstr($value, '#', true);
    }
}