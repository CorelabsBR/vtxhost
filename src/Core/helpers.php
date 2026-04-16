<?php

declare(strict_types=1);

function base_url(string $path = ''): string
{
    $baseUrl = rtrim($_ENV['APP_URL'] ?? 'http://localhost:8000', '/');
    $normalizedPath = ltrim($path, '/');

    return $normalizedPath === '' ? $baseUrl : $baseUrl . '/' . $normalizedPath;
}

function asset(string $path): string
{
    return base_url('assets/' . ltrim($path, '/'));
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function old(string $key, string $default = ''): string
{
    return $_SESSION['_old'][$key] ?? $default;
}