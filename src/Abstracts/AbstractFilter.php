<?php

namespace App\Abstracts;

use App\Entity\Request;
use App\Service\PayloadLoader;

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

    const PAYLOAD_DIRECTORY = __DIR__ . '/../../filterResources/';

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

    private function isStringValidList(string $string): bool
    {
        $string = str_replace(' ', '', $string);
        return
            strlen($string) > 2
            && strpos($string, '[') === 0
            && strpos($string, ']') === strlen($string) - 1
        ;
    }

    /**
     * @param string|array $value
     */
    private function valueFoundInPayload($value, array $payload, bool $strict = true): bool
    {
        if ($strict) {
            if (is_string($value)) {
                return isset($payload[$value]);
            }

            if (is_array($value)) {
                return $this->recursiveArrayTraversal($value, $payload, $strict);
            }
        }

        foreach ($payload as $payloadValue) {
            if (is_string($value)) {
                if (strpos($value, $payloadValue) !== false) {
                    return true;
                }

                continue;
            }

            if (is_array($value)) {
                return $this->recursiveArrayTraversal($value, $payload, $strict);
            }
        }

        return false;
    }

    private function recursiveArrayTraversal(array $value, array $payload, bool $strict): bool
    {
        foreach ($value as $item) {
            if (is_array($item)) {
                if ($this->recursiveArrayTraversal($item, $payload, $strict) === true) {
                    return true;
                }

                continue;
            }

            if (is_string($item) && $this->valueFoundInPayload($item, $payload, $strict)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string|array $value
     */
    final protected function handleCriticalPayload($value): bool
    {
        $payloadFileString = CONFIG['FILTER_' . $this->filterName .'_CRITICAL_PAYLOAD_FILES'];
        if ($this->isStringValidList($payloadFileString)) {
            $payloadFileString = trim($payloadFileString, "[]");
            $payloadFiles = explode(',', $payloadFileString);

            // Another payload file is only loaded if the file before it did not contain the value (performance)
            foreach ($payloadFiles as $payloadFile) {
                $payload = PayloadLoader::loadPayload(self::PAYLOAD_DIRECTORY . trim($payloadFile));

                if ($this->valueFoundInPayload($value, $payload, CONFIG['FILTER_' . $this->filterName . '_CRITICAL_STRICT_MATCH'] === 'true')) {
                    $this->criticalMatch = true;
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param string|array $value
     */
    final protected function handleRegularPayload($value): bool
    {
        $payloadFileString = CONFIG['FILTER_' . $this->filterName .'_PAYLOAD_FILES'];
        if ($this->isStringValidList($payloadFileString)) {
            $payloadFileString = trim($payloadFileString, "[]");
            $payloadFiles = explode(',', $payloadFileString);

            // Another payload file is only loaded if the file before it did not contain the value (performance)
            foreach ($payloadFiles as $payloadFile) {
                $payload = PayloadLoader::loadPayload(self::PAYLOAD_DIRECTORY . trim($payloadFile));

                if ($this->valueFoundInPayload($value, $payload, CONFIG['FILTER_' . $this->filterName .'_STRICT_MATCH'] === 'true')) {
                    return false;
                }
            }
        }

        return true;
    }
}