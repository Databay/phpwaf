<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Exception\PayloadException;
use App\Factory\FilterExceptionFactory;

class GETFilter extends AbstractPayloadFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($request->getServer()['REQUEST_METHOD'] === 'GET' && $this->isFilterActive()) {
            $get = $request->getGet();
            try {
                $this->handlePayload($get);
            } catch (PayloadException $payloadException) {
                throw FilterExceptionFactory::getException($this, $request, $payloadException->getPayloadBlockingType(), 'Malicious GET values detected from file: ' . $payloadException->getPayloadFile());
            }
        }
    }
}