<?php

namespace App\Handler;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Service\Logger;

class RequestHandler
{
    public static function handleRequest(Request $request): bool{
        /** @var AbstractFilter $filter */
		$pass = true;
        foreach (self::getAllFilters() as $filter) {
            $filterResult = $filter->apply($request);

            // false = bad
            if ($filterResult === false) {
                $pass = false;
                $logger = new Logger();
                $blockingType = $filter->getBlockingType();
                $logger->log($blockingType, $request, $filter);

                switch ($blockingType) {
                    case AbstractFilter::BLOCKING_TYPE_HARD:
                        // Do not respond
                        sleep(3); // Prefer 60 seconds
						http_response_code(408); //timeout code
                        exit;
                    case AbstractFilter::BLOCKING_TYPE_REJECT:
                        // Respond with error message
                        http_response_code(400);
                        exit;
                    case AbstractFilter::BLOCKING_TYPE_WARNING:
                        // Log warning but proceed with execution
                        break;
                    default:
                        // Unknown blocking type
                        $logger->log('UNKNOWN', $request, $filter);
                        exit;
                }
            }
        }

        return $pass;
    }

    private static function getAllFilters(): array{

		$filters = [];
		$files = glob(__DIR__ . '/../Filter/*Filter.php');

        if($files === false) {
            return [];
        }
		foreach($files as $file) {
			$className = '\\App\\Filter\\' . str_replace('.php', '', basename($file));
			$filters[] = new $className();
		}
        return $filters;
    }
}