<?php

namespace App\Filter;

use App\Entity\Request;

class HttpFilter extends AbstractFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive()) {
            return (isset($request->getServer()['HTTPS']) && $request->getServer()['HTTPS'] === 'on');
        }

        return true;
    }

    public function getBlockingType(): string
    {
        return isset(CONFIG['FILTER_HTTP_BLOCKING_TYPE']) ? CONFIG['FILTER_HTTP_BLOCKING_TYPE'] : parent::BLOCKING_TYPE_WARNING;
    }

    protected function isFilterActive(): bool
    {
        return isset(CONFIG['FILTER_HTTP_ACTIVE']) ? (CONFIG['FILTER_HTTP_ACTIVE'] === "true") : true;
    }
}