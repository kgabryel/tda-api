<?php

namespace App\User\Infrastructure;

abstract class StringUtils
{
    public static function toCamelCase(string $string): string
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }

    public static function trimContent(?string $content): ?string
    {
        if ($content === null) {
            return null;
        }
        if (!str_starts_with($content, '<p>')) {
            return $content;
        }

        return substr(substr($content, strlen('<p>')), 0, -strlen('</p>'));
    }
}
