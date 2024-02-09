<?php

namespace App\Abstracts;

use App\Exception\PayloadException;
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

    /**
     * @param string|array $value
     * @throws PayloadException
     */
    final protected function handlePayload($value, bool $critical = false)
    {
        if ($critical) {
            $payloadFileString = CONFIG['FILTER_' . $this->filterName .'_CRITICAL_PAYLOAD_FILES'];
            $payloadStrictMatchString = CONFIG['FILTER_' . $this->filterName .'_CRITICAL_STRICT_MATCH'];
        } else {
            $payloadFileString = CONFIG['FILTER_' . $this->filterName .'_PAYLOAD_FILES'];
            $payloadStrictMatchString = CONFIG['FILTER_' . $this->filterName .'_STRICT_MATCH'];
        }

        if ($this->isStringValidList($payloadFileString) && $this->isStringValidList($payloadStrictMatchString)) {
            $payloadFileString = trim($payloadFileString, "[]");
            $payloadStrictMatchString = trim($payloadStrictMatchString, "[]");
            $payloadStrictMatches = explode(',', $payloadStrictMatchString);

            // Another payload file is only loaded if the file before it did not contain the value (performance)
            foreach (explode(',', $payloadFileString) as $key => $payloadFile) {
                $payload = PayloadLoader::load(self::PAYLOAD_DIRECTORY . trim($payloadFile));
                $payloadStrictMatch = isset($payloadStrictMatches[$key]) ? trim($payloadStrictMatches[$key]) === 'true' : true;

                if ($this->valueFoundInPayload($value, $payload, $payloadStrictMatch)) {
                    $this->criticalMatch = $critical;
                    throw new PayloadException($payloadFile, $payloadStrictMatch, $critical);
                }
            }
        }
    }
}