<?php

namespace App\Exception;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Service\Logger;
use Exception;

abstract class FilterException extends Exception
{
    private $filter;

    private $request;

    public function __construct(AbstractFilter $filter, Request $request, $message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->filter = $filter;
        $this->request = $request;
    }

    final public function getFilter(): AbstractFilter
    {
        return $this->filter;
    }

    final public function getRequest(): Request
    {
        return $this->request;
    }

    public function getLogEntryContent(): string
    {
        if (Logger::convertLogLevelStringToInt(CONFIG['LOGGER_LOG_LEVEL']) & Logger::convertLogLevelStringToInt(Logger::DEBUG)) {
            return $this->getFilter()->getLogEntryContent($this->getRequest()) . ' => ' . $this->getMessage();
        }

        return $this->getFilter()->getLogEntryContent($this->getRequest());
    }
}