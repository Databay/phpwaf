<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class DomainFilter extends AbstractFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive() && $this->isStringValidList(CONFIG['FILTER_DOMAIN_ALLOWED_DOMAINS'])) {
            $httpHost = $request->getServer()['HTTP_HOST'];
            $strstr = strstr($httpHost, ':', true);
            $httpHost = ($strstr !== false) ? $strstr : $httpHost;

            $allowedDomains = explode(',', trim(CONFIG['FILTER_DOMAIN_ALLOWED_DOMAINS'], '[]'));

            if (is_array($allowedDomains) && !in_array($httpHost, $allowedDomains, true)) {
                return false;
            }
        }

        return true;
    }
}