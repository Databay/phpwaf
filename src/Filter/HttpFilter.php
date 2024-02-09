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
                throw FilterExceptionFactory::getException($this, $request, 'HTTP request was sent instead of HTTPS');
            }
        }
    }

    public function getBlockingType(): string
    {
        return in_array(CONFIG['FILTER_' . $this->filterName .  '_BLOCKING_TYPE'], parent::BLOCKING_TYPES) ? CONFIG['FILTER_' . $this->filterName .  '_BLOCKING_TYPE'] : parent::BLOCKING_TYPE_WARNING;
    }
}