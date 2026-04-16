<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

final class Auth
{
    public static function user(): ?array
    {
        $userId = $_SESSION['user_id'] ?? null;

        if (! is_int($userId) && ! ctype_digit((string) $userId)) {
            return null;
        }

        return (new User())->find((int) $userId);
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = (new User())->findByEmail($email);

        if (! $user || ! password_verify($password, $user['password'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];

        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function ensureGuest(): void
    {
        if (self::check()) {
            header('Location: ' . base_url('/conta'));
            exit;
        }
    }

    public static function ensureAuthenticated(): void
    {
        if (! self::check()) {
            Session::flash('error', 'Faça login para continuar.');
            header('Location: ' . base_url('/login'));
            exit;
        }
    }

    public static function ensureRoot(): void
    {
        $user = self::user();

        if (! $user || ($user['role'] ?? 'client') !== 'root') {
            Session::flash('error', 'Acesso restrito ao administrador root.');
            header('Location: ' . base_url('/'));
            exit;
        }
    }
}