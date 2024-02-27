<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Exception\PayloadException;
use App\Factory\FilterExceptionFactory;

class URIFilter extends AbstractPayloadFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($this->isFilterActive()) {
            $requestURI = $request->getServer()['REQUEST_URI'];
            try {
                $this->handlePayload($requestURI);
            } catch (PayloadException $payloadException) {
                throw FilterExceptionFactory::getException($this, $request, $payloadException->getPayloadBlockingType(), 'Malicious URI detected from file: ' . $payloadException->getPayloadFile());
            }
        }
    }
}