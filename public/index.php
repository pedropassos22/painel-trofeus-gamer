<?php

require_once __DIR__ . '/../bootstrap.php';

use App\Core\Auth;
use App\Core\Router;

$router = new Router();

require CONFIG_PATH . '/routes.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = parse_url(BASE_URL, PHP_URL_PATH);

if ($basePath && $basePath !== '/') {
    $uri = preg_replace('#^' . preg_quote($basePath, '#') . '#', '', $uri);
}

// Páginas que podem ser acessadas sem estar logado
$publicRoutes = ['/login', '/register'];

$currentRoute = '/' . trim($uri, '/');

if (!Auth::check() && !in_array($currentRoute, $publicRoutes, true)) {
    header('Location: ' . BASE_URL . '/login');

    exit;
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);