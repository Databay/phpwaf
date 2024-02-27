<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Exception\PayloadException;
use App\Factory\FilterExceptionFactory;

class RequestFilter extends AbstractPayloadFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($this->isFilterActive()) {
            $requestArray = $request->getRequest();
            try {
                $this->handlePayload($requestArray);
            } catch (PayloadException $payloadException) {
                throw FilterExceptionFactory::getException($this, $request, $payloadException->getPayloadBlockingType(), 'Malicious request values detected from file: ' . $payloadException->getPayloadFile());
            }
        }
    }
}