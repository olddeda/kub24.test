<?php

declare(strict_types=1);

namespace app\components\cache;

class L1Cache
{
    private static array $cache = [];

    public static function get(string $classKey, string $key): mixed
    {
        if (!isset(self::$cache[$classKey])) {
            return null;
        }
        
        if (!isset(self::$cache[$classKey][$key])) {
            return null;
        }
        
        $cached = self::$cache[$classKey][$key];
        if ($cached['expires'] > time()) {
            return $cached['value'];
        }
        
        unset(self::$cache[$classKey][$key]);
        return null;
    }

    public static function set(string $classKey, string $key, mixed $value, int $duration): void
    {
        if (!isset(self::$cache[$classKey])) {
            self::$cache[$classKey] = [];
        }
        
        self::$cache[$classKey][$key] = [
            'value' => $value,
            'expires' => time() + $duration
        ];
    }

    public static function delete(string $classKey, string $key): void
    {
        if (isset(self::$cache[$classKey])) {
            unset(self::$cache[$classKey][$key]);
        }
    }

    public static function clear(string $classKey): void
    {
        if (isset(self::$cache[$classKey])) {
            self::$cache[$classKey] = [];
        }
    }

    public static function clearAll(): void
    {
        self::$cache = [];
    }
}
