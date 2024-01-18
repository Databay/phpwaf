<?php

namespace App\Filter;

use App\Entity\Request;

abstract class AbstractFilter
{
    const BLOCKING_TYPE_WARNING = 'WARNING';
    const BLOCKING_TYPE_REJECT = 'REJECT';
    const BLOCKING_TYPE_HARD = 'HARD';

    const BLOCKING_TYPES = [
        self::BLOCKING_TYPE_WARNING,
        self::BLOCKING_TYPE_REJECT,
        self::BLOCKING_TYPE_HARD,
    ];

    abstract public function apply(Request $request): bool;

    final protected function isFilterActive(): bool
    {
        return true;
    }

    abstract public function getBlockingType(): string;
}