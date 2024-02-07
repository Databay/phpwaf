<?php

namespace App\Handler;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterCriticalException;
use App\Exception\FilterRejectException;
use App\Exception\FilterTimeoutException;
use App\Exception\FilterWarningException;
use App\Service\IPService;
use App\Service\JailService;
use App\Service\Logger;
use App\Service\UserAgentService;
use Exception;

class RequestHandler
{
    /**
     * @codeCoverageIgnore
     */
    public static function handleRequest(Request $request): bool
    {
        UserAgentService::handleUserAgent($request);
        IPService::handleIP($request);
        $pass = true;

        foreach (self::getAllFilters() as /** @var AbstractFilter $filter **/ $filter) {
            try {
                $filter->apply($request);
            } catch (FilterWarningException $exception) {
                $pass = false;
                Logger::log($exception->getLogEntryContent(), Logger::WARNING); // Log warning but proceed with execution
            } catch (FilterTimeoutException $exception) {
                Logger::log($exception->getLogEntryContent(), Logger::WARNING);
                sleep(max(min(CONFIG['TIMEOUT_LENGTH'], 120), 0));
                http_response_code(408); // Do not respond. Timeout
                exit;
            } catch (FilterRejectException $exception) {
                Logger::log($exception->getLogEntryContent(), Logger::WARNING);
                http_response_code(400); // Respond with error message
                exit;
            } catch (FilterCriticalException $exception) {
                Logger::log($exception->getLogEntryContent(), Logger::CRITICAL);
                JailService::handleBanning($request);
                http_response_code(403); // Ban client and deny access
                exit;
            } catch (Exception $exception) {
                Logger::log($exception->getMessage(), Logger::DEBUG);
                exit; // Unknown blocking type
            }
        }

        if ($pass) {
            Logger::log('Successful request', Logger::DEBUG);
            return true;
        }

        return false;
    }

    private static function getAllFilters(): array
    {
		$files = glob(__DIR__ . '/../Filter/*Filter.php');

        if($files === false) {
            // @codeCoverageIgnoreStart
            return [];
            // @codeCoverageIgnoreEnd
        }

        $filters = [];

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