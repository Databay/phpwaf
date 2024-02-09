<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Exception\PayloadException;
use App\Factory\FilterExceptionFactory;

class CookieFilter extends AbstractPayloadFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($this->isFilterActive()) {
            $cookies = $request->getCookie();
            try {
                $this->handlePayload($cookies, true);
                $this->handlePayload($cookies, false);
            } catch (PayloadException $payloadException) {
                throw FilterExceptionFactory::getException($this, $request, 'Malicious cookie detected from file: ' . $payloadException->getPayloadFile());
            }
        }
    }
}