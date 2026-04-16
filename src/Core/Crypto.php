<?php

declare(strict_types=1);

namespace App\Core;

final class Crypto
{
    public static function encrypt(string $plain): string
    {
        $key = self::key();
        $iv = random_bytes(16);
        $cipher = openssl_encrypt($plain, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        if ($cipher === false) {
            throw new \RuntimeException('Falha ao criptografar dados sensiveis.');
        }

        return base64_encode($iv . $cipher);
    }

    public static function decrypt(?string $payload): string
    {
        if (! is_string($payload) || $payload === '') {
            return '';
        }

        $bin = base64_decode($payload, true);
        if ($bin === false || strlen($bin) < 17) {
            return '';
        }

        $iv = substr($bin, 0, 16);
        $cipher = substr($bin, 16);

        $plain = openssl_decrypt($cipher, 'AES-256-CBC', self::key(), OPENSSL_RAW_DATA, $iv);

        return is_string($plain) ? $plain : '';
    }

    private static function key(): string
    {
        $raw = (string) ($_ENV['APP_KEY'] ?? 'change-this-app-key');

        if ($raw === '') {
            throw new \RuntimeException('APP_KEY nao configurada.');
        }

        return hash('sha256', $raw, true);
    }
}
