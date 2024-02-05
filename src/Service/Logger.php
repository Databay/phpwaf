<?php

namespace App\Service;

class Logger
{
    const DEFAULT_LOGFILE_PATH = __DIR__ . '/../../logs/waf.log';

    const DEBUG = 'DEBUG';
    const INFO = 'INFO';
    const WARNING = 'WARNING';
    const CRITICAL = 'CRITICAL';
    const NONE = 'NONE';

    const LOG_LEVELS = [
        8 => self::DEBUG,
        4 => self::INFO,
        2 => self::WARNING,
        1 => self::CRITICAL,
        0 => self::NONE
    ];

    public static function log(string $content, string $logLevel)
    {
        $configuredLogLevel = self::convertLogLevelStringToInt(CONFIG['LOGGER_LOG_LEVEL']);
        if ($configuredLogLevel === 0 || !in_array($logLevel, self::LOG_LEVELS))  {
            return;
        }

        if (self::convertLogLevelStringToInt($configuredLogLevel) & self::convertLogLevelStringToInt($logLevel)) {
            $logfilePath = (CONFIG['LOGGER_LOGFILE_PATH'] === 'null') ? self::DEFAULT_LOGFILE_PATH : CONFIG['LOGGER_LOGFILE_PATH'];
            file_put_contents($logfilePath, self::getLogEntry($content, $logLevel), FILE_APPEND);
        }
    }

    private static function convertLogLevelStringToInt(string $logLevel): int
    {
        if (is_numeric($logLevel)) {
            return min(max((int) $logLevel, 0), 15);
        }

        $flippedLogLevels = array_flip(self::LOG_LEVELS);
        $logLevel = strtoupper($logLevel);

        return $flippedLogLevels[$logLevel] ?? $flippedLogLevels[self::DEBUG];
    }

    private static function getLogEntry(string $content, string $logLevel): string
    {
        return sprintf(
            '[%s] [%s] %s' . PHP_EOL,
            date('Y-m-d H:i:s'),
            $logLevel,
            $content
        );
    }
}