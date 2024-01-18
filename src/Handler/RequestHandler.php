<?php

namespace App\Handler;

use App\Entity\Request;
use App\Filter\AbstractFilter;
use App\Logger;

class RequestHandler
{
    public static function handleRequest(Request $request): bool
    {
        /** @var AbstractFilter $filter */
        foreach (self::getAllFilters() as $filter) {
            $filterResult = $filter->apply($request);

            // false = bad
            if ($filterResult === false) {
                $pass = false;
                $logger = new Logger();
                $blockingType = $filter->getBlockingType();
                $logger->log(AbstractFilter::BLOCKING_TYPE_HARD, $request, $filter);

                switch ($blockingType) {
                    case AbstractFilter::BLOCKING_TYPE_HARD:
                        // Do not respond
                        sleep(3); // Prefer 60 seconds
                        exit;
                        break;
                    case AbstractFilter::BLOCKING_TYPE_REJECT:
                        // Respond with error message
                        http_response_code(400);
                        exit;
                        break;
                    case AbstractFilter::BLOCKING_TYPE_WARNING:
                        // Log warning but proceed with execution
                        break;
                    default:
                        // Unknown blocking type
                        $logger->log('UNKNOWN', $request, $filter);
                        exit;
                        break;
                }
            }
        }

        return $pass ?? true;
    }

    private static function getAllFilters(): array
    {
        $files = scandir(__DIR__ . '/../Filter');

        if ($files === false) {
            return [];
        }

        $files = array_filter($files, static function ($file) {
            return $file !== 'AbstractFilter.php' && $file !== '.' && $file !== '..';
        });

        $files = array_values($files);

        $files = array_map(static function ($file) {
            return 'App\\Filter\\' . str_replace('.php', '', $file);
        }, $files);

        foreach ($files as $file) {
            $filters[] = new $file();
        }

        return $filters ?? [];
    }
}