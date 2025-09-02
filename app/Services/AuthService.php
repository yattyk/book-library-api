<?php
namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;

final class AuthService
{
    public static function register(string $login, string $password): array
    {
        if (User::findByLogin($login)) {
            throw new \RuntimeException('Login already taken');
        }
        $id = User::create($login, password_hash($password, PASSWORD_DEFAULT));
        return ['id' => $id, 'token' => self::token($id)];
    }

    public static function login(string $login, string $password): array
    {
        $u = User::findByLogin($login);
        if (!$u || !password_verify($password, $u['password_hash'])) {
            throw new \RuntimeException('Invalid credentials');
        }
        return ['id' => $u['id'], 'token' => self::token((int) $u['id'])];
    }

    private static function token(int $userId): string
    {
        $payload = [
            'sub' => $userId,
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24 * 7 
        ];
        return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
    }
}
