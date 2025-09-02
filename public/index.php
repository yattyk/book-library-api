<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Support\Router;
use App\Support\Response;
use App\Database;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . ($_ENV['CORS_ORIGIN'] ?? '*'));
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

// DB init
Database::init([
  'host' => $_ENV['DB_HOST'],
  'port' => (int)$_ENV['DB_PORT'],
  'db'   => $_ENV['DB_DATABASE'],
  'user' => $_ENV['DB_USERNAME'],
  'pass' => $_ENV['DB_PASSWORD'],
]);

$router = new Router();
require __DIR__ . '/../routes/api.php';

try {
  $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
  Response::json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
}
