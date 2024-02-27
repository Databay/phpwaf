<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Factory\FilterExceptionFactory;

class HttpFilter extends AbstractFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($this->isFilterActive()) {
            $key = CONFIG['FILTER_HTTP_HTTPS_KEY'] ?? 'HTTPS';
            if (!isset($request->getServer()[$key]) || $request->getServer()[$key] !== 'on') {
                throw FilterExceptionFactory::getException($this, $request, null, 'HTTP request was sent instead of HTTPS');
            }
        }
    }
}