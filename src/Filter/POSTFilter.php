<?php

namespace App\Filter;

use App\Abstracts\AbstractPayloadFilter;
use App\Entity\Request;

class POSTFilter extends AbstractPayloadFilter
{
    public function apply(Request $request): bool
    {
        if ($request->getServer()['REQUEST_METHOD'] === 'POST' && $this->isFilterActive()) {
            $post = $request->getPost();
            if ($this->handleCriticalPayload($post) === false || $this->handleRegularPayload($post) === false) {
                return false;
            }
        }

        return true;
    }
}