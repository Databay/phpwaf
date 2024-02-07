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
            $requestArray = $request->getRequest();
            if ($this->handleCriticalPayload($requestArray) === false || $this->handleRegularPayload($requestArray) === false) {
                throw FilterExceptionFactory::getException($this, $request, 'Malicious request values detected');
            }
        }
    }
}