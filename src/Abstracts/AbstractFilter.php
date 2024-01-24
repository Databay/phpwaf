<?php

namespace App\Abstracts;

use App\Entity\Request;

abstract class AbstractFilter
{
    const BLOCKING_TYPE_WARNING = 'WARNING';
    const BLOCKING_TYPE_REJECT = 'REJECT';
    const BLOCKING_TYPE_TIMEOUT = 'TIMEOUT';
    const BLOCKING_TYPE_CRITICAL = 'CRITICAL';

    const BLOCKING_TYPES = [
        self::BLOCKING_TYPE_WARNING,
        self::BLOCKING_TYPE_REJECT,
        self::BLOCKING_TYPE_TIMEOUT,
        self::BLOCKING_TYPE_CRITICAL,
    ];

    abstract public function apply(Request $request): bool;

    abstract protected function isFilterActive(): bool;

    abstract public function getBlockingType(): string;
}