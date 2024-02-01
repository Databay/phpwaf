<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;

class HeaderFilter extends AbstractPayloadFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive()) {
            $headers = $request->getHeaders();
            if ($this->handleCriticalPayload($headers) === false || $this->handleRegularPayload($headers) === false) {
                return false;
            }
        }

        return true;
    }
}