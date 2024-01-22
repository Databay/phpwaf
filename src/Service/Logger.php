<?php

namespace App\Service;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class Logger
{
    const DEFAULT_LOGFILE_PATH = __DIR__ . '/../../logs';

    public function log(string $type, Request $request, AbstractFilter $filter)
    {
        $logfilePath = (CONFIG['LOGGER_LOGFILE_PATH'] === 'null') ? self::DEFAULT_LOGFILE_PATH : CONFIG['LOGGER_LOGFILE_PATH'];
        $logFile = fopen(file_exists($logfilePath) ? $logfilePath : self::DEFAULT_LOGFILE_PATH, 'ab');

        fwrite($logFile, $this->getLogEntry($type, $request, $filter));
        fclose($logFile);
    }

    private function getLogEntry(string $type, Request $request, AbstractFilter $filter): string
    {
        return sprintf(
            '[%s] [%s] %s %s %s [%s]' . PHP_EOL,
            date('Y-m-d H:i:s'),
            $type,
            $request->getServer()['REMOTE_ADDR'],
            $request->getServer()['REQUEST_METHOD'],
            $request->getServer()['REQUEST_URI'],
            get_class($filter)
        );
    }
}