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
    $params = [];

    if (!$action) {
        foreach ($this->routes[$method] ?? [] as $route => $routeAction) {

            $pattern = preg_replace(
                '#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#',
                '([^/]+)',
                $route
            );

            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {

                array_shift($matches);

                preg_match_all(
                    '#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#',
                    $route,
                    $names
                );

                foreach ($names[1] as $index => $name) {
                    $params[$name] = $matches[$index];
                }

                $action = $routeAction;

                break;
            }
        }
    }

    if (!$action) {
        http_response_code(404);

        echo '404 - Página não encontrada';

        return;
    }

    if (is_callable($action)) {
        call_user_func($action, ...array_values($params));

        return;
    }

    [$class, $method] = $action;

    $controller = new $class();

    $controller->$method(...array_values($params));
}


    private function normalize(string $uri): string
    {
        $uri = '/' . trim($uri, '/');

        return $uri === '//' ? '/' : $uri;
    }
}