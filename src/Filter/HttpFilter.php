<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class HttpFilter extends AbstractFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive() && is_array($request->getServer())) {
            $key = CONFIG['FILTER_HTTP_HTTPS_KEY'] ?? 'HTTPS';
            return isset($request->getServer()[$key]) && $request->getServer()[$key] === 'on';
        }

        return true;
    }

    public function getBlockingType(): string
    {
        return in_array(CONFIG['FILTER_' . $this->filterName .  '_BLOCKING_TYPE'], parent::BLOCKING_TYPES) ? CONFIG['FILTER_' . $this->filterName .  '_BLOCKING_TYPE'] : parent::BLOCKING_TYPE_WARNING;
    }
}