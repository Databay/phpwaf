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
    final protected function handlePayload($value)
    {
        $payloadFileString = CONFIG['FILTER_' . $this->filterName .'_PAYLOAD_FILES'];
        $payloadStrictMatchString = CONFIG['FILTER_' . $this->filterName .'_STRICT_MATCH'];
        $payloadBlockingTypeString = CONFIG['FILTER_' . $this->filterName .'_BLOCKING_TYPE'];

        if ($this->isStringValidList($payloadFileString)) {
            $payloadFileString = trim($payloadFileString, "[]");
            $payloadFiles = explode(',', $payloadFileString);
        } else {
            $payloadFiles = [$payloadFileString];
        }

        if ($this->isStringValidList($payloadStrictMatchString)) {
            $payloadStrictMatchString = trim($payloadStrictMatchString, "[]");
            $payloadStrictMatches = explode(',', $payloadStrictMatchString);
        } else {
            $payloadStrictMatch = $payloadStrictMatchString === 'true';
        }

        if ($this->isStringValidList($payloadBlockingTypeString)) {
            $payloadBlockingTypeString = trim($payloadBlockingTypeString, "[]");
            $payloadBlockingTypes = explode(',', $payloadBlockingTypeString);
        } else {
            $payloadBlockingType = in_array($payloadBlockingTypeString, AbstractFilter::BLOCKING_TYPES, true) ? $payloadBlockingTypeString : AbstractFilter::BLOCKING_TYPE_WARNING;
        }

        // Another payload file is only loaded if the file before it did not contain the value (performance)
        foreach ($payloadFiles as $key => $payloadFile) {
            $payload = PayloadLoader::load(self::PAYLOAD_DIRECTORY . trim($payloadFile));
            $strictMatch = $payloadStrictMatch ?? (isset($payloadStrictMatches[$key]) ? $payloadStrictMatches[$key] === 'true' : true);

            if ($this->valueFoundInPayload($value, $payload, $strictMatch)) {
                $payloadBlockingType = $payloadBlockingType ?? ((isset($payloadBlockingTypes[$key]) && in_array($payloadBlockingTypes[$key], AbstractFilter::BLOCKING_TYPES)) ? $payloadBlockingTypes[$key] : AbstractFilter::BLOCKING_TYPE_WARNING);

                throw new PayloadException($payloadBlockingType, $payloadFile, $strictMatch);
            }
        }
    }
}