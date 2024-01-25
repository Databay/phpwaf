<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class URIFilter extends AbstractFilter
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