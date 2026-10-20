<?php

namespace App\Core;

class Auth
{
    public static function check(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function id(): ?int
    {
        return self::check() ? (int) $_SESSION['user_id'] : null;
    }

    public static function name(): ?string
    {
        return $_SESSION['user_nome'] ?? null;
    }

    public static function login(int $id, string $nome): void
    {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $id;
        $_SESSION['user_nome'] = $nome;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id'], $_SESSION['user_nome']);

        session_regenerate_id(true);
    }
}