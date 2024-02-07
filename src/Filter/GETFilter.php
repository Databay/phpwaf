<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Factory\FilterExceptionFactory;

class GETFilter extends AbstractPayloadFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($request->getServer()['REQUEST_METHOD'] === 'GET' && $this->isFilterActive()) {
            $get = $request->getGet();
            if ($this->handleCriticalPayload($get) === false || $this->handleRegularPayload($get) === false) {
                throw FilterExceptionFactory::getException($this, $request, 'Malicious GET values detected');
            }
        }
    }
}