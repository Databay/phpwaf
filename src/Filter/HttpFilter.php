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
        $key = CONFIG['FILTER_HTTP_HTTPS_KEY'] ?? 'HTTPS';
        if ($this->isFilterActive() && is_array($request->getServer()) && isset($request->getServer()[$key]) && $request->getServer()[$key] !== 'on') {
            throw new FilterException($this);
        }
    }

    public function getBlockingType(): string
    {
        return in_array(CONFIG['FILTER_' . $this->filterName .  '_BLOCKING_TYPE'], parent::BLOCKING_TYPES) ? CONFIG['FILTER_' . $this->filterName .  '_BLOCKING_TYPE'] : parent::BLOCKING_TYPE_WARNING;
    }
}