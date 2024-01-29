<?php

namespace App\Abstracts;

abstract class AbstractFileLoader
{
    protected static function removeComments(string $value): string
    {
        return (strpos($value, '#') === false) ? $value : strstr($value, '#', true);
    }
}