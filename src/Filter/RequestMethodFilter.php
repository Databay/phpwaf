<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterException;

class RequestMethodFilter extends AbstractFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($this->isFilterActive() && CONFIG['FILTER_REQUESTMETHOD_' . $request->getServer()['REQUEST_METHOD'] . '_ALLOW'] === 'false') {
            throw new FilterException($this);
        }
    }
}