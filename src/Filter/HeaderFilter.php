<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;

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
                throw new FilterException($this);
            }
        }
    }
}