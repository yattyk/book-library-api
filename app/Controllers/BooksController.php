<?php
namespace App\Controllers;

use App\Support\Response;
use App\Models\Book;

final class BooksController
{
    public function myBooks(): string
    {
        $uid = (int) $GLOBALS['auth_user_id'];
        return Response::json(Book::byOwner($uid));
    }

    public function show(array $p): string
    {
        $uid = (int) $GLOBALS['auth_user_id'];
        $book = Book::find((int) $p['id'], $uid);
        if (!$book) {
            return Response::json(['error' => 'Not found'], 404);
        }
        return Response::json([
            'id'    => $book['id'],
            'title' => $book['title'],
            'text'  => $book['text'],
            'url'   => $book['source_url']
        ]);
    }

    public function create(): string
    {
        $uid = (int) $GLOBALS['auth_user_id'];
        try {
            $id = \App\Services\BookService::createFromInput($uid);
            return Response::json(['id' => $id], 201);
        } catch (\Throwable $e) {
            return Response::json(['error' => $e->getMessage()], 422);
        }
    }

    public function update(array $p): string
    {
        $uid = (int) $GLOBALS['auth_user_id'];
        $d = json_decode(file_get_contents('php://input'), true) ?? [];

        $ok = \App\Models\Book::update(
            (int) $p['id'],
            $uid,
            (string) ($d['title'] ?? ''),
            $d['text'] ?? null
        );

        return Response::json(
            $ok ? ['status' => 'ok'] : ['error' => 'Not found'],
            $ok ? 200 : 404
        );
    }
}
