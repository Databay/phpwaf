<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class HttpFilter extends AbstractFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive() && is_array($request->getServer()) && isset($request->getServer()['HTTPS'])) {
            return $request->getServer()['HTTPS'] === 'on';
        }

        return true;
    }

    public function getBlockingType(): string
    {
        return in_array(CONFIG['FILTER_' . $this->filterName .  '_BLOCKING_TYPE'], parent::BLOCKING_TYPES) ? CONFIG['FILTER_' . $this->filterName .  '_BLOCKING_TYPE'] : parent::BLOCKING_TYPE_WARNING;
    }
}