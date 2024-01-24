<?php

namespace App\Abstracts;

abstract class FileLoader
{
    protected static function removeComments(string $value): string
    {
        return (strpos($value, '#') === false) ? $value : strstr($value, '#', true);
    }
}