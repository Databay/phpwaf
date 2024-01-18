<?php

namespace App\Filter;

abstract class AbstractFilter
{
    abstract public function apply($request): bool;
}