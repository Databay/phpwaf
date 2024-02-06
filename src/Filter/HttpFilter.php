<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterException;

class HttpFilter extends AbstractFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($this->isFilterActive() && is_array($request->getServer()) && isset($request->getServer()['HTTPS']) && $request->getServer()['HTTPS'] !== 'on') {
            throw new FilterException($this);
        }
    }

    public function getBlockingType(): string
    {
        return in_array(CONFIG['FILTER_' . $this->filterName .  '_BLOCKING_TYPE'], parent::BLOCKING_TYPES) ? CONFIG['FILTER_' . $this->filterName .  '_BLOCKING_TYPE'] : parent::BLOCKING_TYPE_WARNING;
    }
}