<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Exception\PayloadException;
use App\Factory\FilterExceptionFactory;

class HeaderFilter extends AbstractPayloadFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($this->isFilterActive()) {
            $headers = $request->getHeaders();
            try {
                $this->handlePayload($headers, true);
                $this->handlePayload($headers, false);
            } catch (PayloadException $payloadException) {
                throw FilterExceptionFactory::getException($this, $request, 'Malicious header values detected from file: ' . $payloadException->getPayloadFile());
            }
        }
    }
}