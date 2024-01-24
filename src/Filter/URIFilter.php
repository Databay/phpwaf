<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Service\PayloadLoader;

class URIFilter extends AbstractFilter
{
    const PAYLOAD_DIRECTORY = __DIR__ . '/../../filterResources/';

    private $criticalMatch = false;

    public function apply(Request $request): bool
    {
        if ($this->isFilterActive()) {
            $payloadFileString = CONFIG['FILTER_URI_CRITICAL_PAYLOAD_FILES'];
            if ($this->isPayloadFileStringValid($payloadFileString)) {
                $payloadFileString = trim($payloadFileString, "[]");
                $payloadFiles = explode(',', $payloadFileString);

                // Another payload file is only loaded if the file before it did not contain the value (performance)
                foreach ($payloadFiles as $payloadFile) {
                    $payload = PayloadLoader::loadPayload(self::PAYLOAD_DIRECTORY . trim($payloadFile));

                    if ($this->valueFoundInPayload($request->getServer()['REQUEST_URI'], $payload, CONFIG['FILTER_URI_CRITICAL_STRICT_MATCH'] === 'true')) {
                        $this->criticalMatch = true;
                        return false;
                    }
                }
            }

            $payloadFileString = trim(CONFIG['FILTER_URI_PAYLOAD_FILES']);
            if ($this->isPayloadFileStringValid($payloadFileString)) {
                $payloadFileString = trim($payloadFileString, "[]");
                $payloadFiles = explode(',', $payloadFileString);

                // Another payload file is only loaded if the file before it did not contain the value (performance)
                foreach ($payloadFiles as $payloadFile) {
                    $payload = PayloadLoader::loadPayload(self::PAYLOAD_DIRECTORY . trim($payloadFile));

                    if ($this->valueFoundInPayload($request->getServer()['REQUEST_URI'], $payload, CONFIG['FILTER_URI_STRICT_MATCH'] === 'true')) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function getBlockingType(): string
    {
        if ($this->criticalMatch === true) {
            return
                in_array(CONFIG['FILTER_URI_CRITICAL_BLOCKING_TYPE'], parent::BLOCKING_TYPES)
                    ? CONFIG['FILTER_URI_CRITICAL_BLOCKING_TYPE']
                    : parent::BLOCKING_TYPE_CRITICAL
            ;
        }

        return
            in_array(CONFIG['FILTER_URI_BLOCKING_TYPE'], parent::BLOCKING_TYPES)
                ? CONFIG['FILTER_URI_BLOCKING_TYPE']
                : parent::BLOCKING_TYPE_WARNING
        ;
    }

    private function valueFoundInPayload(string $value, array $payload, bool $strictMode = true): bool
    {
        if ($strictMode) {
            if (isset($payload[$value])) {
                return true;
            }
        } else {
            foreach ($payload as $payloadValue) {
                if (strpos($value, $payloadValue) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    private function isPayloadFileStringValid(string $payloadFileString): bool
    {
        $payloadFileString = str_replace(' ', '', $payloadFileString);
        return
            strlen($payloadFileString) > 2
            && strpos($payloadFileString, '[') === 0
            && strpos($payloadFileString, ']') === strlen($payloadFileString) - 1
        ;
    }

    protected function isFilterActive(): bool
    {
        return (CONFIG['FILTER_URI_ACTIVE'] === 'true');
    }
}