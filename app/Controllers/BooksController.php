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
}
