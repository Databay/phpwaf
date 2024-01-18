<?php

namespace App\Filter;

use App\Entity\Request;

class HttpFilter extends AbstractFilter
{
    public function apply(Request $request): bool
    {
        return $request->getServer()['HTTPS'] !== 'on';
    }

    public function getBlockingType(): string
    {
        return parent::BLOCKING_TYPE_HARD;
    }
}