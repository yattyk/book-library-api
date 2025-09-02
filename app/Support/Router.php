<?php
namespace App\Support;

final class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable $handler, array $middleware = []): void
    {
        $this->routes[] = compact('method','path','handler','middleware');
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        foreach ($this->routes as $route) {
            $pattern = '#^' . preg_replace('#\\{(\\w+)\\}#', '(?P<$1>[^/]+)', $route['path']) . '$#';
            if ($method === $route['method'] && preg_match($pattern, $path, $m)) {
                $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
                foreach ($route['middleware'] as $mw) { $mw(); }
                echo call_user_func($route['handler'], $params) ?? '';
                return;
            }
        }
        \App\Support\Response::json(['error' => 'Not found'], 404);
    }
}
