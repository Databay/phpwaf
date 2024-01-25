<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;

class URIFilter extends AbstractPayloadFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive()) {
            $requestURI = $request->getServer()['REQUEST_URI'];
            if ($this->handleCriticalPayload($requestURI) === false || $this->handleRegularPayload($requestURI) === false) {
                return false;
            }
        }

        return true;
    }
}