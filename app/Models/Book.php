<?php
namespace App\Models;

use App\Database;

final class Book
{
    public static function create(int $userId, string $title, ?string $text, ?string $url): int
    {
        $sql = 'INSERT INTO books (user_id, title, text, source_url)
                VALUES (:u, :t, :x, :s)';
        $st = Database::pdo()->prepare($sql);
        $st->execute([':u' => $userId, ':t' => $title, ':x' => $text, ':s' => $url]);
        return (int) Database::pdo()->lastInsertId();
    }

    public static function byOwner(int $userId): array
    {
        $st = Database::pdo()->prepare(
            'SELECT id, title FROM books
             WHERE user_id = :u AND deleted_at IS NULL
             ORDER BY id'
        );
        $st->execute([':u' => $userId]);
        return $st->fetchAll();
    }

    public static function find(int $id, int $ownerId): ?array
    {
        $st = Database::pdo()->prepare(
            'SELECT * FROM books
             WHERE id = :id AND user_id = :u AND deleted_at IS NULL'
        );
        $st->execute([':id' => $id, ':u' => $ownerId]);
        $r = $st->fetch();
        return $r ?: null;
    }
}
