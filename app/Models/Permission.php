<?php
namespace App\Models;

use App\Database;

final class Permission
{
    public static function grant(int $owner, int $grantee): bool
    {
        $st = Database::pdo()->prepare(
            'INSERT IGNORE INTO permissions (owner_id, grantee_id) VALUES (:o,:g)'
        );
        return $st->execute([':o' => $owner, ':g' => $grantee]);
    }

    public static function canView(int $owner, int $grantee): bool
    {
        $st = Database::pdo()->prepare(
            'SELECT 1 FROM permissions WHERE owner_id=:o AND grantee_id=:g'
        );
        $st->execute([':o' => $owner, ':g' => $grantee]);
        return (bool) $st->fetchColumn();
    }
}
