<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;

class CookieFilter extends AbstractPayloadFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive()) {
            $cookies = $request->getCookie();
            if ($this->handleCriticalPayload($cookies) === false || $this->handleRegularPayload($cookies) === false) {
                return false;
            }
        }

        return true;
    }
}