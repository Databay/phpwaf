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

    protected $filterName;

    protected $criticalMatch = false;

    public function __construct()
    {
        $this->filterName = strtoupper(str_replace('Filter', '', (new \ReflectionClass(static::class))->getShortName()));
    }

    abstract public function apply(Request $request): bool;

    public function getBlockingType(): string
    {
        if ($this->criticalMatch === true) {
            return
                in_array(CONFIG['FILTER_' . $this->filterName . '_CRITICAL_BLOCKING_TYPE'], self::BLOCKING_TYPES, true)
                    ? CONFIG['FILTER_' . $this->filterName . '_CRITICAL_BLOCKING_TYPE']
                    : self::BLOCKING_TYPE_CRITICAL
                ;
        }

        return
            in_array(CONFIG['FILTER_' . $this->filterName . '_BLOCKING_TYPE'], self::BLOCKING_TYPES, true)
                ? CONFIG['FILTER_' . $this->filterName . '_BLOCKING_TYPE']
                : self::BLOCKING_TYPE_WARNING
            ;
    }

    protected function isFilterActive(): bool
    {
        return (CONFIG['FILTER_' . $this->filterName . '_ACTIVE'] === 'true');
    }

    protected function isStringValidList(string $string): bool
    {
        $string = str_replace(' ', '', $string);
        return
            strlen($string) > 2
            && strpos($string, '[') === 0
            && strpos($string, ']') === strlen($string) - 1
        ;
    }
}