<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Factory\FilterExceptionFactory;

class CookieFilter extends AbstractPayloadFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($this->isFilterActive()) {
            $cookies = $request->getCookie();
            if ($this->handleCriticalPayload($cookies) === false || $this->handleRegularPayload($cookies) === false) {
                throw FilterExceptionFactory::getException($this, $request, 'Malicious cookie detected');
            }
        }
    }
}