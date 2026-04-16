<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use Throwable;

final class Order
{
    public function createFromCart(int $userId, int $cartId, array $items, string $provisionPasswordEnc): int
    {
        $pdo = Database::connection();
        $total = 0.0;
        foreach ($items as $item) {
            $total += (float) $item['unit_price'] * (int) $item['quantity'];
        }

        $pdo->beginTransaction();

        try {
            $externalReference = 'order-' . $userId . '-' . bin2hex(random_bytes(6));

            $stOrder = $pdo->prepare(
                'INSERT INTO orders (user_id, cart_id, status, total_amount, currency, provision_password_enc, external_reference)
                 VALUES (:user_id, :cart_id, \'pending\', :total_amount, \'BRL\', :provision_password_enc, :external_reference)'
            );

            $stOrder->execute([
                'user_id' => $userId,
                'cart_id' => $cartId,
                'total_amount' => $total,
                'provision_password_enc' => $provisionPasswordEnc,
                'external_reference' => $externalReference,
            ]);

            $orderId = (int) $pdo->lastInsertId();

            $stItem = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, unit_price, product_name)
                 VALUES (:order_id, :product_id, :quantity, :unit_price, :product_name)'
            );

            foreach ($items as $item) {
                $stItem->execute([
                    'order_id' => $orderId,
                    'product_id' => (int) $item['product_id'],
                    'quantity' => (int) $item['quantity'],
                    'unit_price' => (float) $item['unit_price'],
                    'product_name' => (string) $item['name'],
                ]);
            }

            $pdo->commit();
            return $orderId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public function setMercadoPagoPreference(int $orderId, string $preferenceId): void
    {
        $st = Database::connection()->prepare('UPDATE orders SET mp_preference_id = :preference WHERE id = :id');
        $st->execute(['preference' => $preferenceId, 'id' => $orderId]);
    }

    public function markPaidByExternalReference(string $externalReference, string $paymentId): ?array
    {
        $pdo = Database::connection();

        $st = $pdo->prepare(
            'UPDATE orders
             SET status = \'paid\', mp_payment_id = :payment_id, paid_at = NOW()
             WHERE external_reference = :external_reference'
        );
        $st->execute([
            'payment_id' => $paymentId,
            'external_reference' => $externalReference,
        ]);

        return $this->findByExternalReference($externalReference);
    }

    public function findById(int $id): ?array
    {
        $st = Database::connection()->prepare('SELECT * FROM orders WHERE id = :id LIMIT 1');
        $st->execute(['id' => $id]);

        return $st->fetch() ?: null;
    }

    public function findByExternalReference(string $externalReference): ?array
    {
        $st = Database::connection()->prepare('SELECT * FROM orders WHERE external_reference = :external_reference LIMIT 1');
        $st->execute(['external_reference' => $externalReference]);

        return $st->fetch() ?: null;
    }

    public function items(int $orderId): array
    {
        $sql = 'SELECT oi.*, p.category, p.location, p.cpu, p.ram, p.storage, p.bandwidth, p.ddos_protection
                FROM order_items oi
                INNER JOIN products p ON p.id = oi.product_id
                WHERE oi.order_id = :order_id
                ORDER BY oi.id ASC';

        $st = Database::connection()->prepare($sql);
        $st->execute(['order_id' => $orderId]);

        return $st->fetchAll();
    }

    public function updateStatus(int $orderId, string $status): void
    {
        $st = Database::connection()->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $st->execute(['status' => $status, 'id' => $orderId]);
    }

    public function byUser(int $userId, int $limit = 20): array
    {
        $st = Database::connection()->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC LIMIT :lim');
        $st->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $st->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $st->execute();

        return $st->fetchAll();
    }
}
