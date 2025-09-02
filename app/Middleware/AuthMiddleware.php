<?php
namespace App\Middleware;

use App\Support\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class AuthMiddleware
{
    public static function ensure(): void
    {
        $hdr = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/Bearer\\s+(.*)/', $hdr, $m)) {
            echo Response::json(['error' => 'Unauthorized'], 401);
            exit;
        }
        try {
            $decoded = JWT::decode($m[1], new Key($_ENV['JWT_SECRET'], 'HS256'));
            $GLOBALS['auth_user_id'] = (int) $decoded->sub;
        } catch (\Throwable $e) {
            echo Response::json(['error' => 'Invalid token'], 401);
            exit;
        }
    }
}
