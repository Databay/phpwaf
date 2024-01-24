<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Service\PayloadLoader;

class URIFilter extends AbstractFilter
{
    const PAYLOAD_DIRECTORY = __DIR__ . '/../../filterResources/';

    public function apply(Request $request): bool
    {
        if ($this->executeFilter($request)) {
            $payloadFileString = trim(CONFIG['FILTER_URI_PAYLOAD_FILES']);

            if ($this->isPayloadFileStringValid($payloadFileString)) {
                $payloadFileString = trim($payloadFileString, "[]");
                $payloadFiles = explode(',', $payloadFileString);

                // Another payload file is only loaded if the file before it did not contain the value (performance)
                foreach ($payloadFiles as $payloadFile) {
                    $payload = PayloadLoader::loadPayload(self::PAYLOAD_DIRECTORY . trim($payloadFile));

                    if ($this->valueFoundInPayload($request->getServer()['REQUEST_URI'], $payload, CONFIG['FILTER_URI_EXACT_MATCH'] === 'true')) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function getBlockingType(): string
    {
        return
            isset(CONFIG['FILTER_URI_BLOCKING_TYPE'])
            && in_array(CONFIG['FILTER_URI_BLOCKING_TYPE'], parent::BLOCKING_TYPES)
                ? CONFIG['FILTER_URI_BLOCKING_TYPE']
                : parent::BLOCKING_TYPE_WARNING
            ;
    }

    private function executeFilter(Request $request): bool
    {
        return
            $this->isFilterActive()
            && is_array($request->getServer())
            && defined('CONFIG')
            && isset($request->getServer()['REQUEST_URI'], CONFIG['FILTER_URI_PAYLOAD_FILES'], CONFIG['FILTER_URI_EXACT_MATCH'])
        ;
    }

    private function valueFoundInPayload(string $value, array $payload, bool $exactMatch = true): bool
    {
        foreach ($payload as $payloadValue) {
            if ($exactMatch) {
                if ($value === $payloadValue) {
                    return true;
                }
            } elseif (strpos($value, $payloadValue) !== false) {
                return true;
            }
        }

        return false;
    }

    private function isPayloadFileStringValid(string $payloadFileString): bool
    {
        return
            strpos($payloadFileString, '[') === 0
            && strpos($payloadFileString, ']') === strlen($payloadFileString) - 1
        ;
    }

    protected function isFilterActive(): bool
    {
        return isset(CONFIG['FILTER_URI_ACTIVE']) ? (CONFIG['FILTER_URI_ACTIVE'] === 'true') : true;
    }
}