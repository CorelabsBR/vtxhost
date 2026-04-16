<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Product
{
    public function find(int $id): ?array
    {
        $st = Database::connection()->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $st->execute(['id' => $id]);

        return $st->fetch() ?: null;
    }

    public function all(): array
    {
        return Database::connection()->query('SELECT * FROM products ORDER BY category, location, price_monthly')->fetchAll();
    }

    public function featured(int $limit = 6): array
    {
        $statement = Database::connection()->prepare('SELECT * FROM products WHERE featured = 1 ORDER BY sort_order ASC, price_monthly ASC LIMIT :limit');
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function byCategory(string $category): array
    {
        $statement = Database::connection()->prepare('SELECT * FROM products WHERE category = :category ORDER BY location, price_monthly');
        $statement->execute(['category' => $category]);

        return $statement->fetchAll();
    }

    public function create(array $data): void
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO products (name, category, location, cpu, ram, storage, bandwidth, ddos_protection, price_monthly, highlight, featured, sort_order)
             VALUES (:name, :category, :location, :cpu, :ram, :storage, :bandwidth, :ddos_protection, :price_monthly, :highlight, :featured, :sort_order)'
        );

        $statement->execute($data);
    }

    public function update(array $data): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE products
             SET name = :name,
                 category = :category,
                 location = :location,
                 cpu = :cpu,
                 ram = :ram,
                 storage = :storage,
                 bandwidth = :bandwidth,
                 ddos_protection = :ddos_protection,
                 price_monthly = :price_monthly,
                 highlight = :highlight,
                 featured = :featured,
                 sort_order = :sort_order
             WHERE id = :id'
        );

        $statement->execute($data);
    }

    public function delete(int $id): void
    {
        $statement = Database::connection()->prepare('DELETE FROM products WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}