<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;
use App\Exception\FilterException;

class POSTFilter extends AbstractPayloadFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($request->getServer()['REQUEST_METHOD'] === 'POST' && $this->isFilterActive()) {
            $post = $request->getPost();
            if ($this->handleCriticalPayload($post) === false || $this->handleRegularPayload($post) === false) {
                throw new FilterException($this);
            }
        }
    }
}