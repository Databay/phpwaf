<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Factory\FilterExceptionFactory;

class HeaderFilter extends AbstractPayloadFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($this->isFilterActive()) {
            $headers = $request->getHeaders();
            if ($this->handleCriticalPayload($headers) === false || $this->handleRegularPayload($headers) === false) {
                throw FilterExceptionFactory::getException($this, $request, 'Malicious header values detected');
            }
        }
    }
}