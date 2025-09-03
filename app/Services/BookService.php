<?php
namespace App\Services;

use App\Models\Book;
use RuntimeException;

final class BookService
{
    public static function createFromInput(int $userId): int
    {
        $title = trim($_POST['title'] ?? '');
        $text  = $_POST['text'] ?? null;
        $url   = null;

        if ($title === '') {
            throw new RuntimeException('Title is required');
        }

        // Если текст не указан, но загружен файл
        if (!$text && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $content = file_get_contents($_FILES['file']['tmp_name']);
            $text = mb_convert_encoding($content, 'UTF-8');
        }

        return Book::create($userId, $title, $text, $url);
    }

    public static function saveExternal(int $userId, array $data): int
    {
        $title = trim($data['title'] ?? '');
        $url   = $data['url'] ?? null;
        $desc  = $data['description'] ?? null;

        if ($title === '') {
            throw new \RuntimeException('Title is required');
        }

        return \App\Models\Book::create($userId, $title, $desc, $url);
    }
}
