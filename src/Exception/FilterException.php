<?php

namespace App\Exception;

use App\Abstracts\AbstractFilter;
use Exception;

class FilterException extends Exception
{
    private $filter;

    public function __construct(AbstractFilter $filter, $message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->filter = $filter;
    }

    public function getFilter(): AbstractFilter
    {
        return $this->filter;
    }
}