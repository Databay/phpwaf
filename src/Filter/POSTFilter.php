<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class POSTFilter extends AbstractFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive()) {
            $post = $request->getPost();
            if ($this->handleCriticalPayload($post) === false || $this->handleRegularPayload($post) === false) {
                return false;
            }
        }

        return true;
    }
}