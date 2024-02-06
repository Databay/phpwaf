<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;

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
                throw new FilterException($this);
            }
        }
    }
}