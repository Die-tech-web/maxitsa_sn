<?php

namespace App\Core;

class Router
{
    private static array $routes = [];

    public static function get(string $path, string $controller, string $method, array $middlewares = []): void
    {
        self::$routes['GET'][$path] = [
            'controller' => $controller,
            'method' => $method,
            'middlewares' => $middlewares
        ];
    }

    public static function post(string $path, string $controller, string $method, array $middlewares = []): void
    {
        self::$routes['POST'][$path] = [
            'controller' => $controller,
            'method' => $method,
            'middlewares' => $middlewares
        ];
    }

    public static function resolve(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset(self::$routes[$requestMethod][$requestPath])) {
            $route = self::$routes[$requestMethod][$requestPath];
            $controllerClass = $route['controller'];
            $method = $route['method'];

            $controller = new $controllerClass();
            $controller->$method();
        } else {
            http_response_code(404);
            echo "Page non trouvée";
        }
    }
}

