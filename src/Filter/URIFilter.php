<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class URIFilter extends AbstractFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive() && is_array($request->getServer()) && isset($request->getServer()['HTTP_HOST'])) {
            return explode(':', $request->getServer()['HTTP_HOST'])[0] !== 'localhost';
        }

        return true;
    }

    public function getBlockingType(): string
    {
        return isset(CONFIG['FILTER_URI_BLOCKING_TYPE']) && in_array(CONFIG['FILTER_URI_BLOCKING_TYPE'], parent::BLOCKING_TYPES) ? CONFIG['FILTER_URI_BLOCKING_TYPE'] : parent::BLOCKING_TYPE_WARNING;
    }

    protected function isFilterActive(): bool
    {
        return isset(CONFIG['FILTER_URI_ACTIVE']) ? (CONFIG['FILTER_URI_ACTIVE'] === "true") : true;
    }
}