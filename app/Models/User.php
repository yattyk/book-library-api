<?php
namespace App\Models;

use App\Database;

final class User
{
    public static function create(string $login, string $hash): int
    {
        $sql = 'INSERT INTO users (login, password_hash) VALUES (:l, :p)';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([':l' => $login, ':p' => $hash]);
        return (int) Database::pdo()->lastInsertId();
    }

    public static function findByLogin(string $login): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM users WHERE login = :l');
        $stmt->execute([':l' => $login]);
        $u = $stmt->fetch();
        return $u ?: null;
    }
}
