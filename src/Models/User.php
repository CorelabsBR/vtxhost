<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class User
{
    public function find(int $id): ?array
    {
        $statement = Database::connection()->prepare('SELECT id, name, email, role, created_at FROM users WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $statement = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $email]);

        return $statement->fetch() ?: null;
    }

    public function verifyPassword(string $email, string $plainPassword): bool
    {
        $user = $this->findByEmail($email);

        if (! $user || ! is_string($user['password'] ?? null)) {
            return false;
        }

        return password_verify($plainPassword, $user['password']);
    }

    public function all(): array
    {
        return Database::connection()->query('SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
    }

    public function create(string $name, string $email, string $password, string $role = 'client'): void
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)'
        );

        $statement->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
        ]);
    }
}