<?php

namespace App\Service;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class Logger
{
    const DEFAULT_LOGFILE_PATH = __DIR__ . '/../../logs/waf.log';

    public static function log(string $type, Request $request, AbstractFilter $filter)
    {
        $logfilePath = (CONFIG['LOGGER_LOGFILE_PATH'] === 'null') ? self::DEFAULT_LOGFILE_PATH : CONFIG['LOGGER_LOGFILE_PATH'];
        file_put_contents($logfilePath, self::getLogEntry($type, $request, $filter), FILE_APPEND);
    }

    private static function getLogEntry(string $type, Request $request, AbstractFilter $filter): string
    {
        return sprintf(
            '[%s] [%s] %s %s %s [%s]' . PHP_EOL,
            date('Y-m-d H:i:s'),
            in_array($type, AbstractFilter::BLOCKING_TYPES) ? $type : 'UNKNOWN',
            $request->getServer()[CONFIG['IP_ADDRESS_KEY']],
            $request->getServer()['REQUEST_METHOD'],
            $request->getServer()['REQUEST_URI'],
            get_class($filter)
        );
    }
}