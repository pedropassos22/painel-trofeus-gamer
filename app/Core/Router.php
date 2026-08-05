<?php

namespace App\Core;

class Router
{
    private array $routes = [];


    public function get(string $uri, callable|array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }


    public function post(string $uri, callable|array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }


    private function addRoute(
        string $method,
        string $uri,
        callable|array $action
    ): void
    {
        $this->routes[$method][$this->normalize($uri)] = $action;
    }


    public function dispatch(string $method, string $uri): void
    {
        $uri = $this->normalize($uri);

        $action = $this->routes[$method][$uri] ?? null;


        if (!$action) {

            http_response_code(404);

            echo '404 - Página não encontrada';

            return;

        }


        if (is_callable($action)) {

            call_user_func($action);

            return;

        }


        [$class, $method] = $action;


        $controller = new $class();

        $controller->$method();

    }


    private function normalize(string $uri): string
    {
        $uri = '/' . trim($uri, '/');

        return $uri === '//' ? '/' : $uri;
    }
}