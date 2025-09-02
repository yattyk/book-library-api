<?php
namespace App\Support;

final class Response
{
    public static function json($data, int $code = 200): string
    {
        http_response_code($code);
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
