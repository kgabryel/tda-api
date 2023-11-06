<?php

namespace App\Core;

use Closure;
use Illuminate\Support\Facades\Cache as BaseCache;

class Cache
{
    public static function forget(string $key): void
    {
        BaseCache::driver('array')->forget($key);
    }

    public static function add(string $key, mixed $value): void
    {
        BaseCache::driver('array')->add($key, $value);
    }

    public static function get(string $key): mixed
    {
        return BaseCache::driver('array')->get($key);
    }

    public static function remember(string $key, Closure $callback): mixed
    {
        return BaseCache::driver('array')->remember($key, 10, $callback);
    }
}
