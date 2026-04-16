<?php

declare(strict_types=1);

namespace App\Services;

final class MercadoPagoService
{
    private string $accessToken;
    private string $baseUrl;

    public function __construct()
    {
        $this->accessToken = (string) ($_ENV['MP_ACCESS_TOKEN'] ?? '');
        $this->baseUrl = rtrim((string) ($_ENV['MP_BASE_URL'] ?? 'https://api.mercadopago.com'), '/');
    }

    public function enabled(): bool
    {
        return $this->accessToken !== '';
    }

    public function createPreference(array $order, array $items): array
    {
        $payloadItems = [];

        foreach ($items as $item) {
            $payloadItems[] = [
                'id' => (string) $item['product_id'],
                'title' => (string) $item['product_name'],
                'quantity' => (int) $item['quantity'],
                'currency_id' => 'BRL',
                'unit_price' => (float) $item['unit_price'],
            ];
        }

        $payload = [
            'external_reference' => (string) $order['external_reference'],
            'notification_url' => base_url('/webhook/mercadopago'),
            'auto_return' => 'approved',
            'back_urls' => [
                'success' => base_url('/checkout/sucesso'),
                'failure' => base_url('/checkout/falha'),
                'pending' => base_url('/checkout/pendente'),
            ],
            'items' => $payloadItems,
        ];

        return $this->request('POST', '/checkout/preferences', $payload);
    }

    public function getPayment(string $paymentId): array
    {
        return $this->request('GET', '/v1/payments/' . rawurlencode($paymentId));
    }

    private function request(string $method, string $path, ?array $payload = null): array
    {
        if (! $this->enabled()) {
            throw new \RuntimeException('Mercado Pago nao configurado (MP_ACCESS_TOKEN).');
        }

        $url = $this->baseUrl . $path;
        $ch = curl_init($url);
        if ($ch === false) {
            throw new \RuntimeException('Falha ao iniciar requisicao HTTP.');
        }

        $headers = [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($payload !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        }

        $raw = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($raw === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException('Erro HTTP Mercado Pago: ' . $err);
        }

        curl_close($ch);

        $data = json_decode($raw, true);
        if (! is_array($data)) {
            throw new \RuntimeException('Resposta invalida do Mercado Pago.');
        }

        if ($httpCode >= 400) {
            throw new \RuntimeException('Mercado Pago retornou erro: ' . ($data['message'] ?? 'sem detalhes'));
        }

        return $data;
    }
}
