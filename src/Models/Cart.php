<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Cart
{
    public function getOpenByUser(int $userId): ?array
    {
        $st = Database::connection()->prepare('SELECT * FROM carts WHERE user_id = :user_id AND status = \'open\' ORDER BY id DESC LIMIT 1');
        $st->execute(['user_id' => $userId]);

        return $st->fetch() ?: null;
    }

    public function getOrCreateOpen(int $userId): array
    {
        $cart = $this->getOpenByUser($userId);
        if ($cart) {
            return $cart;
        }

        $st = Database::connection()->prepare('INSERT INTO carts (user_id, status) VALUES (:user_id, \'open\')');
        $st->execute(['user_id' => $userId]);

        return $this->getOpenByUser($userId) ?? [];
    }

    public function items(int $cartId): array
    {
        $sql = 'SELECT ci.id, ci.cart_id, ci.product_id, ci.quantity, ci.unit_price,
                       p.name, p.category, p.location, p.cpu, p.ram, p.storage, p.bandwidth, p.ddos_protection
                FROM cart_items ci
                INNER JOIN products p ON p.id = ci.product_id
                WHERE ci.cart_id = :cart_id
                ORDER BY ci.id ASC';

        $st = Database::connection()->prepare($sql);
        $st->execute(['cart_id' => $cartId]);

        return $st->fetchAll();
    }

    public function addItem(int $cartId, int $productId, float $unitPrice, int $quantity = 1): void
    {
        $sql = 'INSERT INTO cart_items (cart_id, product_id, quantity, unit_price)
                VALUES (:cart_id, :product_id, :quantity, :unit_price)
                ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity), unit_price = VALUES(unit_price)';

        $st = Database::connection()->prepare($sql);
        $st->execute([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
        ]);
    }

    public function removeItem(int $cartId, int $itemId): void
    {
        $st = Database::connection()->prepare('DELETE FROM cart_items WHERE id = :id AND cart_id = :cart_id');
        $st->execute(['id' => $itemId, 'cart_id' => $cartId]);
    }

    public function total(int $cartId): float
    {
        $st = Database::connection()->prepare('SELECT COALESCE(SUM(quantity * unit_price), 0) AS total FROM cart_items WHERE cart_id = :cart_id');
        $st->execute(['cart_id' => $cartId]);
        $row = $st->fetch();

        return (float) ($row['total'] ?? 0);
    }

    public function markConverted(int $cartId): void
    {
        $st = Database::connection()->prepare('UPDATE carts SET status = \'converted\' WHERE id = :id');
        $st->execute(['id' => $cartId]);
    }
}
