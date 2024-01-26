<?php

namespace App\Abstracts;

use App\Service\PayloadLoader;

abstract class AbstractPayloadFilter extends AbstractFilter
{
    const PAYLOAD_DIRECTORY = __DIR__ . '/../../filterResources/';

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

    abstract public static function getInstance(): self;

    abstract public function loadPayload(string $path): array;

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
                $payload = static::getInstance()->loadPayload(self::PAYLOAD_DIRECTORY . trim($payloadFile));

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
                $payload = static::getInstance()->loadPayload(self::PAYLOAD_DIRECTORY . trim($payloadFile));

                if ($this->valueFoundInPayload($value, $payload, CONFIG['FILTER_' . $this->filterName .'_STRICT_MATCH'] === 'true')) {
                    return false;
                }
            }
        }

        return true;
    }
}