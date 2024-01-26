<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class RequestMethodFilter extends AbstractFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive()) {
            return CONFIG['FILTER_REQUEST_METHOD_' . $request->getServer()['REQUEST_METHOD'] . '_ALLOW'] !== 'false';
        }

        return true;
    }
}