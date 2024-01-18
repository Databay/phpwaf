<?php

namespace App\Handler;

use App\Entity\Request;
use App\Filter\AbstractFilter;

class RequestHandler
{
    public static function handleRequest(Request $request): bool
    {
        /** @var AbstractFilter $filter */
        foreach (self::getAllFilters() as $filter) {
            $pass = $filter->apply($request) ?: false;
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