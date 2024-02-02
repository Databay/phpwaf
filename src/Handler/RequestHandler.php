<?php

namespace App\Handler;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Service\IPService;
use App\Service\Logger;
use App\Service\UserAgentService;

class RequestHandler
{
    /**
     * @codeCoverageIgnore
     */
    public static function handleRequest(Request $request): bool{
        UserAgentService::handleUserAgent($request);
        IPService::handleIP($request);

        /** @var AbstractFilter $filter */
		$pass = true;
        foreach (self::getAllFilters() as $filter) {
            $filterResult = $filter->apply($request);

            // false = bad
            if ($filterResult === false) {
                $pass = false;
                $blockingType = $filter->getBlockingType();
                Logger::log($blockingType, $request, $filter);

                switch ($blockingType) {
                    case AbstractFilter::BLOCKING_TYPE_TIMEOUT:
                        sleep(3); // Prefer 60 seconds
						http_response_code(408); // Do not respond. Timeout
                        exit;
                    case AbstractFilter::BLOCKING_TYPE_REJECT:
                        http_response_code(400); // Respond with error message
                        exit;
                    case AbstractFilter::BLOCKING_TYPE_WARNING:
                        break; // Log warning but proceed with execution
                    case AbstractFilter::BLOCKING_TYPE_CRITICAL:
                        if (CONFIG['USERAGENT_BAN_ACTIVE'] === 'true') {
                            UserAgentService::banUserAgent(UserAgentService::getClientIdentifier($request));
                        } elseif (CONFIG['IP_BAN_ACTIVE'] === 'true') {
                            IPService::banIP($_SERVER['REMOTE_ADDR']);
                        }
                        http_response_code(403);
                        exit;
                    default:
                        exit; // Unknown blocking type
                }
            }
        }

        return $pass;
    }

    private static function getAllFilters(): array{

		$filters = [];
		$files = glob(__DIR__ . '/../Filter/*Filter.php');

        if($files === false) {
            // @codeCoverageIgnoreStart
            return [];
            // @codeCoverageIgnoreEnd
        }

		foreach($files as $file) {
			$className = '\\App\\Filter\\' . str_replace('.php', '', basename($file));
            $filter = new $className();
            if ($filter instanceof AbstractFilter) {
                $filters[] = $filter;
            }
		}

        return $filters;
    }
}