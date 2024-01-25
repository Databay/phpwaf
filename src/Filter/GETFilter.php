<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;

class GETFilter extends AbstractPayloadFilter
{
    public function apply(Request $request): bool
    {
        if ($request->getServer()['REQUEST_METHOD'] === 'GET' && $this->isFilterActive()) {
            $get = $request->getGet();
            if ($this->handleCriticalPayload($get) === false || $this->handleRegularPayload($get) === false) {
                return false;
            }
        }

        return true;
    }
}