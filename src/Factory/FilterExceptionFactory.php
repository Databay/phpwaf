<?php

namespace App\Factory;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterCriticalException;
use App\Exception\FilterException;
use App\Exception\FilterRejectException;
use App\Exception\FilterTimeoutException;
use App\Exception\FilterWarningException;
use Exception;

abstract class FilterExceptionFactory
{
    public static function getException(AbstractFilter $filter, Request $request, string $blockingType = null, string $message = '', int $code = 0, Exception $previous = null): FilterException
    {
        switch ($blockingType ?? $filter->getBlockingType()) {
            case AbstractFilter::BLOCKING_TYPE_REJECT:
                return new FilterRejectException($filter, $request, $message, $code, $previous);
            case AbstractFilter::BLOCKING_TYPE_TIMEOUT:
                return new FilterTimeoutException($filter, $request, $message, $code, $previous);
            case AbstractFilter::BLOCKING_TYPE_CRITICAL:
                return new FilterCriticalException($filter, $request, $message, $code, $previous);
            case AbstractFilter::BLOCKING_TYPE_WARNING:
            default:
                return new FilterWarningException($filter, $request, $message, $code, $previous);
        }
    }
}