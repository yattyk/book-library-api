<?php
namespace App;

use PDO;

final class Database
{
    private static PDO $pdo;

    public static function init(array $cfg): void
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $cfg['host'], $cfg['port'], $cfg['db']
        );
        self::$pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public static function pdo(): PDO { return self::$pdo; }
}
