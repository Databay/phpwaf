<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Factory\FilterExceptionFactory;

class RequestFilter extends AbstractPayloadFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($this->isFilterActive()) {
            $request = $request->getRequest();
            if ($this->handleCriticalPayload($request) === false || $this->handleRegularPayload($request) === false) {
                throw FilterExceptionFactory::getException($this, $request, 'Malicious request values detected');
            }
        }
    }
}