<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class GETFilter extends AbstractFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive()) {
            $get = $request->getGet();
            if ($this->handleCriticalPayload($get) === false || $this->handleRegularPayload($get) === false) {
                return false;
            }
        }

        return true;
    }
}