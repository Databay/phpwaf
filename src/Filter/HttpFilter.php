<?php

namespace App\Filter;

class HttpFilter extends AbstractFilter
{
    public function apply($request): bool
    {
        return $request->getServer()['HTTPS'] !== 'on';
    }
}