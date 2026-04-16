<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class HostedService
{
    public function create(array $data): int
    {
        $sql = 'INSERT INTO services (
                    user_id, order_id, order_item_id, product_id, service_name, status,
                    pterodactyl_user_id, pterodactyl_server_id, panel_url, panel_username, panel_password_enc, last_error
                ) VALUES (
                    :user_id, :order_id, :order_item_id, :product_id, :service_name, :status,
                    :pterodactyl_user_id, :pterodactyl_server_id, :panel_url, :panel_username, :panel_password_enc, :last_error
                )';

        $st = Database::connection()->prepare($sql);
        $st->execute([
            'user_id' => $data['user_id'],
            'order_id' => $data['order_id'],
            'order_item_id' => $data['order_item_id'],
            'product_id' => $data['product_id'],
            'service_name' => $data['service_name'],
            'status' => $data['status'] ?? 'pending',
            'pterodactyl_user_id' => $data['pterodactyl_user_id'] ?? null,
            'pterodactyl_server_id' => $data['pterodactyl_server_id'] ?? null,
            'panel_url' => $data['panel_url'] ?? null,
            'panel_username' => $data['panel_username'] ?? null,
            'panel_password_enc' => $data['panel_password_enc'] ?? null,
            'last_error' => $data['last_error'] ?? null,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function byUser(int $userId): array
    {
        $sql = 'SELECT s.*, p.name AS product_name
                FROM services s
                INNER JOIN products p ON p.id = s.product_id
                WHERE s.user_id = :user_id
                ORDER BY s.id DESC';

        $st = Database::connection()->prepare($sql);
        $st->execute(['user_id' => $userId]);

        return $st->fetchAll();
    }

    public function findByIdAndUser(int $id, int $userId): ?array
    {
        $st = Database::connection()->prepare('SELECT * FROM services WHERE id = :id AND user_id = :user_id LIMIT 1');
        $st->execute(['id' => $id, 'user_id' => $userId]);

        return $st->fetch() ?: null;
    }
}
