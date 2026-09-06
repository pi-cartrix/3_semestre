<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

class Router
{
    private array $routes = [];

    public function get(string $path, string $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, string $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function put(string $path, string $handler): void
    {
        $this->add('PUT', $path, $handler);
    }

    public function delete(string $path, string $handler): void
    {
        $this->add('DELETE', $path, $handler);
    }

    public function patch(string $path, string $handler): void
    {
        $this->add('PATCH', $path, $handler);
    }

    public function add(string $method, string $path, string $handler): void
    {
        $pattern = $this->compilePattern($path);
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->invoke($route['handler'], $params);
                return;
            }
        }

        $this->invoke('ErrorController@notFound', []);
    }

    private function compilePattern(string $path): string
    {
        $escaped = preg_quote($path, '#');
        $escaped = preg_replace('#\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\}#', '(?P<$1>[^/]+)', $escaped);

        return '#^' . $escaped . '$#';
    }

    private function invoke(string $handler, array $params): void
    {
        [$controller, $action] = explode('@', $handler);
        $controllerClass = 'App\\Controllers\\' . $controller;

        if (!class_exists($controllerClass) || !method_exists($controllerClass, $action)) {
            throw new RuntimeException(sprintf(
                'Handler not found: %s::%s',
                $controllerClass,
                $action
            ));
        }

        $controllerInstance = new $controllerClass();
        $controllerInstance->{$action}(...$params);
    }
}