<?php

namespace App\Core;

class App
{
    private static array $services = [];

    public static function set(string $key, mixed $service): void
    {
        self::$services[$key] = $service;
    }

    public static function get(string $key): mixed
    {
        if (!isset(self::$services[$key])) {
            throw new \RuntimeException("Serviço '{$key}' não registrado.");
        }

        return self::$services[$key];
    }
}