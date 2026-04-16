<?php

declare(strict_types=1);

namespace App\Services;

final class PterodactylService
{
    private string $panelUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->panelUrl = rtrim((string) ($_ENV['PTERODACTYL_PANEL_URL'] ?? ''), '/');
        $this->apiKey = (string) ($_ENV['PTERODACTYL_APP_API_KEY'] ?? '');
    }

    public function enabled(): bool
    {
        return $this->panelUrl !== '' && $this->apiKey !== '';
    }

    public function panelUrl(): string
    {
        return $this->panelUrl;
    }

    public function findUserByEmail(string $email): ?array
    {
        $resp = $this->request('GET', '/api/application/users?filter[email]=' . rawurlencode($email));
        $data = $resp['data'] ?? [];

        if (! is_array($data) || $data === []) {
            return null;
        }

        $first = $data[0]['attributes'] ?? null;
        return is_array($first) ? $first : null;
    }

    public function createUser(array $user, string $plainPassword): array
    {
        $name = trim((string) ($user['name'] ?? 'Cliente VortexHost'));
        $parts = preg_split('/\s+/', $name) ?: [];
        $firstName = $parts[0] ?? 'Cliente';
        $lastName = implode(' ', array_slice($parts, 1));
        if ($lastName === '') {
            $lastName = 'VortexHost';
        }

        $usernameBase = preg_replace('/[^a-zA-Z0-9_.-]/', '', strtolower((string) ($user['email'] ?? 'cliente')));
        $username = substr($usernameBase ?: ('cliente' . random_int(1000, 9999)), 0, 30);

        $payload = [
            'email' => (string) $user['email'],
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'password' => $plainPassword,
            'language' => 'pt-BR',
        ];

        $resp = $this->request('POST', '/api/application/users', $payload);
        return (array) ($resp['attributes'] ?? []);
    }

    public function createServer(array $payload): array
    {
        $resp = $this->request('POST', '/api/application/servers', $payload);
        return (array) ($resp['attributes'] ?? []);
    }

    private function request(string $method, string $path, ?array $payload = null): array
    {
        if (! $this->enabled()) {
            throw new \RuntimeException('Pterodactyl nao configurado.');
        }

        $url = $this->panelUrl . $path;

        $ch = curl_init($url);
        if ($ch === false) {
            throw new \RuntimeException('Falha ao iniciar requisicao HTTP Pterodactyl.');
        }

        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Accept: Application/vnd.pterodactyl.v1+json',
            'Content-Type: application/json',
        ];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);

        if ($payload !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        }

        $raw = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($raw === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException('Erro HTTP Pterodactyl: ' . $err);
        }

        curl_close($ch);

        $data = json_decode($raw, true);
        if (! is_array($data)) {
            throw new \RuntimeException('Resposta invalida do Pterodactyl.');
        }

        if ($code >= 400) {
            $msg = $data['errors'][0]['detail'] ?? $data['message'] ?? 'sem detalhes';
            throw new \RuntimeException('Pterodactyl retornou erro: ' . $msg);
        }

        return $data;
    }
}
