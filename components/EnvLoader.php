<?php

declare(strict_types=1);

namespace app\components;

use Dotenv\Dotenv;

class EnvLoader
{
    public static function load(): void
    {
        if (class_exists(Dotenv::class)) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__));
            $dotenv->safeLoad();
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }

    public static function getString(string $key, string $default = ''): string
    {
        return (string) self::get($key, $default);
    }

    public static function getInt(string $key, int $default = 0): int
    {
        return (int) self::get($key, $default);
    }

    public static function getBool(string $key, bool $default = false): bool
    {
        $value = self::get($key, $default);
        if (is_string($value)) {
            return in_array(strtolower($value), ['true', '1', 'yes', 'on'], true);
        }
        return (bool) $value;
    }
}
