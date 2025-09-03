<?php
namespace App\Controllers;

use App\Support\Response;

final class SearchController
{
    // GET /api/search?q=строка&provider=google|mif
    public function search(): string
    {
        $q = trim((string) ($_GET['q'] ?? ''));
        $provider = $_GET['provider'] ?? 'google';

        if ($q === '') {
            return Response::json(['error' => 'Query required'], 422);
        }

        $results = [];

        if ($provider === 'google') {
            $url = 'https://www.googleapis.com/books/v1/volumes?q=' . urlencode($q);
            $json = @file_get_contents($url);
            if ($json !== false) {
                $data = json_decode($json, true);
                foreach ($data['items'] ?? [] as $item) {
                    $results[] = [
                        'id'    => $item['id'] ?? null,
                        'title' => $item['volumeInfo']['title'] ?? '',
                        'url'   => $item['volumeInfo']['infoLink'] ?? null,
                        'description' => $item['volumeInfo']['description'] ?? null
                    ];
                }
            }
        } elseif ($provider === 'mif') {
            $url = 'https://www.mann-ivanov-ferber.ru/book/search.ajax?q=' . urlencode($q);
            $json = @file_get_contents($url);
            if ($json !== false) {
                $data = json_decode($json, true);
                foreach ($data ?? [] as $item) {
                    $results[] = [
                        'id'    => $item['id'] ?? null,
                        'title' => $item['title'] ?? '',
                        'url'   => $item['url'] ?? null,
                        'description' => null
                    ];
                }
            }
        }

        return Response::json(['items' => $results]);
    }

    public function save(): string
    {
        $uid = (int) $GLOBALS['auth_user_id'];
        $d = json_decode(file_get_contents('php://input'), true) ?? [];

        try {
            $id = \App\Services\BookService::saveExternal($uid, $d);
            return Response::json(['id' => $id], 201);
        } catch (\Throwable $e) {
            return Response::json(['error' => $e->getMessage()], 422);
        }
    }
}
