<?php

namespace App;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class Logger
{
    public function log(string $type, Request $request, AbstractFilter $filter)
    {
        $logFile = fopen(__DIR__ . '/../logs', 'ab');
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