<?php

namespace App\Shared\Infrastructure\Utils;

use Exception;
use Illuminate\Http\Request;

abstract class CollectionUtils
{
    public static function getNumericValues(Request $request): array
    {
        try {
            $ids = json_decode($request->get('ids'), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception) {
            return [];
        }
        $ids = array_filter($ids, static fn($value) => is_numeric($value));

        return array_map(static fn($value) => (int)$value, $ids);
    }

    public static function getStringValues(Request $request): array
    {
        try {
            $ids = json_decode($request->get('ids'), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception) {
            return [];
        }
        $ids = array_filter($ids, static fn($value) => is_string($value));

        return array_map(static fn($value) => (string)$value, $ids);
    }
}
