<?php

namespace App\Handler;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Service\IPService;
use App\Service\Logger;
use App\Service\UserAgentService;
use Exception;

class RequestHandler
{
    /**
     * @codeCoverageIgnore
     */
    public static function handleRequest(Request $request): bool{
        UserAgentService::handleUserAgent($request);
        IPService::handleIP($request);
        $pass = true;

        foreach (self::getAllFilters() as /** @var AbstractFilter $filter **/ $filter) {
            try {
                $filter->apply($request);
            } catch (FilterException $exception) {
                $pass = false;
                switch ($exception->getFilter()->getBlockingType()) {
                    case AbstractFilter::BLOCKING_TYPE_TIMEOUT:
                        Logger::log($filter->getLogEntryContent($request), Logger::WARNING);
                        sleep(3); // Prefer 60 seconds
						http_response_code(408); // Do not respond. Timeout
                        exit;
                    case AbstractFilter::BLOCKING_TYPE_REJECT:
                        Logger::log($filter->getLogEntryContent($request), Logger::WARNING);
                        http_response_code(400); // Respond with error message
                        exit;
                    case AbstractFilter::BLOCKING_TYPE_WARNING:
                        Logger::log($filter->getLogEntryContent($request), Logger::WARNING);
                        break; // Log warning but proceed with execution
                    case AbstractFilter::BLOCKING_TYPE_CRITICAL:
                        Logger::log($filter->getLogEntryContent($request), Logger::CRITICAL);
                        if (CONFIG['USERAGENT_BAN_ACTIVE'] === 'true') {
                            UserAgentService::banUserAgent(UserAgentService::getClientIdentifier($request));
                        } elseif (CONFIG['IP_BAN_ACTIVE'] === 'true') {
                            IPService::banIP($request->getServer()[CONFIG['IP_ADDRESS_KEY']]);
                        }
                        http_response_code(403);
                        exit;
                    default:
                        Logger::log($filter->getLogEntryContent($request), Logger::WARNING);
                        exit; // Unknown blocking type
                }
            } catch (Exception $exception) {
                Logger::log('An error occurred: ' . $exception->getMessage(), Logger::CRITICAL);
                exit;
            }
        }

        if ($pass) {
            Logger::log('Request successful', Logger::INFO);
            return true;
        }

        return false;
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