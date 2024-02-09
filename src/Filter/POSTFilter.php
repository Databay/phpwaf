<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Exception\PayloadException;
use App\Factory\FilterExceptionFactory;

class POSTFilter extends AbstractPayloadFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($request->getServer()['REQUEST_METHOD'] === 'POST' && $this->isFilterActive()) {
            $post = $request->getPost();
            try {
                $this->handlePayload($post, true);
                $this->handlePayload($post, false);
            } catch (PayloadException $payloadException) {
                throw FilterExceptionFactory::getException($this, $request, 'Malicious POST values detected from file: ' . $payloadException->getPayloadFile());
            }
        }
    }
}