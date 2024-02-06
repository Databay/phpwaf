<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;

class URIFilter extends AbstractPayloadFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($this->isFilterActive()) {
            $requestURI = $request->getServer()['REQUEST_URI'];
            if ($this->handleCriticalPayload($requestURI) === false || $this->handleRegularPayload($requestURI) === false) {
                throw new FilterException($this);
            }
        }
    }
}