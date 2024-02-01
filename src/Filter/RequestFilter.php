<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;

class RequestFilter extends AbstractPayloadFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive()) {
            $request = $request->getRequest();
            if ($this->handleCriticalPayload($request) === false || $this->handleRegularPayload($request) === false) {
                return false;
            }
        }

        return true;
    }
}