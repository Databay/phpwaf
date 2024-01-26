<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class RequestMethodFilter extends AbstractFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive()) {
            return CONFIG['FILTER_REQUESTMETHOD_' . $request->getServer()['REQUEST_METHOD'] . '_ALLOW'] !== 'false';
        }

        return true;
    }
}