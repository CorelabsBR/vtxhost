<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Crypto;
use App\Models\HostedService;
use App\Models\Order;
use App\Models\User;

final class ProvisioningService
{
    public function provisionOrder(int $orderId, string $sitePassword): void
    {
        $orders = new Order();
        $order = $orders->findById($orderId);
        if (! $order) {
            return;
        }

        $orders->updateStatus($orderId, 'provisioning');

        $user = (new User())->find((int) $order['user_id']);
        if (! $user) {
            $orders->updateStatus($orderId, 'failed');
            return;
        }

        $items = $orders->items($orderId);
        $services = new HostedService();
        $ptero = new PterodactylService();

        if (! $ptero->enabled()) {
            foreach ($items as $item) {
                $services->create([
                    'user_id' => (int) $order['user_id'],
                    'order_id' => $orderId,
                    'order_item_id' => (int) $item['id'],
                    'product_id' => (int) $item['product_id'],
                    'service_name' => (string) $item['product_name'],
                    'status' => 'failed',
                    'last_error' => 'Pterodactyl nao configurado no ambiente.',
                ]);
            }
            $orders->updateStatus($orderId, 'failed');
            return;
        }

        try {
            $pteroUser = $ptero->findUserByEmail((string) $user['email']);
            if (! $pteroUser) {
                $pteroUser = $ptero->createUser($user, $sitePassword);
            }

            foreach ($items as $item) {
                $serverPayload = $this->serverPayloadFromItem($item, (int) $pteroUser['id'], (int) $user['id']);
                $server = $ptero->createServer($serverPayload);

                $services->create([
                    'user_id' => (int) $order['user_id'],
                    'order_id' => $orderId,
                    'order_item_id' => (int) $item['id'],
                    'product_id' => (int) $item['product_id'],
                    'service_name' => (string) $item['product_name'],
                    'status' => 'active',
                    'pterodactyl_user_id' => (int) $pteroUser['id'],
                    'pterodactyl_server_id' => (int) ($server['id'] ?? 0),
                    'panel_url' => rtrim($ptero->panelUrl(), '/') . '/server/' . ($server['identifier'] ?? ''),
                    'panel_username' => (string) $user['email'],
                    'panel_password_enc' => Crypto::encrypt($sitePassword),
                ]);
            }

            $orders->updateStatus($orderId, 'provisioned');
        } catch (\Throwable $e) {
            foreach ($items as $item) {
                $services->create([
                    'user_id' => (int) $order['user_id'],
                    'order_id' => $orderId,
                    'order_item_id' => (int) $item['id'],
                    'product_id' => (int) $item['product_id'],
                    'service_name' => (string) $item['product_name'],
                    'status' => 'failed',
                    'last_error' => $e->getMessage(),
                ]);
            }
            $orders->updateStatus($orderId, 'failed');
        }
    }

    private function serverPayloadFromItem(array $item, int $pteroUserId, int $siteUserId): array
    {
        $eggId = (int) ($_ENV['PTERODACTYL_DEFAULT_EGG_ID'] ?? 1);
        $nodeId = (int) ($_ENV['PTERODACTYL_DEFAULT_NODE_ID'] ?? 1);
        $locationId = (int) ($_ENV['PTERODACTYL_DEFAULT_LOCATION_ID'] ?? 1);
        $dockerImage = (string) ($_ENV['PTERODACTYL_DEFAULT_DOCKER_IMAGE'] ?? 'ghcr.io/pterodactyl/yolks:java_17');
        $startup = (string) ($_ENV['PTERODACTYL_DEFAULT_STARTUP'] ?? 'java -Xms128M -Xmx{{SERVER_MEMORY}}M -jar server.jar');

        $memoryMb = $this->parseMemoryMb((string) ($item['ram'] ?? '2048 MB'));
        $diskMb = $this->parseDiskMb((string) ($item['storage'] ?? '10240 MB'));
        $cpuLimit = $this->parseCpuLimit((string) ($item['cpu'] ?? '100'));

        return [
            'name' => (string) $item['product_name'] . ' #' . $siteUserId,
            'user' => $pteroUserId,
            'egg' => $eggId,
            'docker_image' => $dockerImage,
            'startup' => $startup,
            'environment' => [
                'SERVER_JARFILE' => 'server.jar',
                'MINECRAFT_VERSION' => 'latest',
            ],
            'limits' => [
                'memory' => $memoryMb,
                'swap' => 0,
                'disk' => $diskMb,
                'io' => 500,
                'cpu' => $cpuLimit,
                'threads' => null,
            ],
            'feature_limits' => [
                'databases' => 2,
                'allocations' => 2,
                'backups' => 3,
            ],
            'allocation' => [
                'default' => (int) ($_ENV['PTERODACTYL_DEFAULT_ALLOCATION_ID'] ?? 1),
            ],
            'deploy' => [
                'locations' => [$locationId],
                'dedicated_ip' => false,
                'port_range' => [],
            ],
            'start_on_completion' => true,
            'node' => $nodeId,
        ];
    }

    private function parseMemoryMb(string $ram): int
    {
        if (preg_match('/([0-9]+(?:[.,][0-9]+)?)\s*GB/i', $ram, $m)) {
            return max(512, (int) (floatval(str_replace(',', '.', $m[1])) * 1024));
        }
        if (preg_match('/([0-9]+)\s*MB/i', $ram, $m)) {
            return max(512, (int) $m[1]);
        }

        return 2048;
    }

    private function parseDiskMb(string $storage): int
    {
        if (preg_match('/([0-9]+(?:[.,][0-9]+)?)\s*TB/i', $storage, $m)) {
            return max(10240, (int) (floatval(str_replace(',', '.', $m[1])) * 1024 * 1024));
        }
        if (preg_match('/([0-9]+(?:[.,][0-9]+)?)\s*GB/i', $storage, $m)) {
            return max(10240, (int) (floatval(str_replace(',', '.', $m[1])) * 1024));
        }
        if (preg_match('/([0-9]+)\s*MB/i', $storage, $m)) {
            return max(10240, (int) $m[1]);
        }

        return 20480;
    }

    private function parseCpuLimit(string $cpu): int
    {
        if (preg_match('/([0-9]+)\s*v?cpu/i', $cpu, $m)) {
            return max(100, (int) $m[1] * 100);
        }

        return 200;
    }
}
