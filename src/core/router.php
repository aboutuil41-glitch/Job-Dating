<?php
namespace App\Core;

class Router
{
    private static array $routes = [];
    private static ?Router $instance = null;



    public static function getRouter(): Router
    {
        return self::$instance ??= new self();
    }

    private function register(string $route, string $method, $action): void
    {
        self::$routes[$method][trim($route, '/')] = $action;
    }

    public function get(string $route, $action): void
    {
        $this->register($route, 'GET', $action);
    }

    public function post(string $route, $action): void
    {
        $this->register($route, 'POST', $action);
    }

    public function delete(string $route, $action): void
    {
        $this->register($route, 'DELETE', $action);
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        foreach (self::$routes[$method] ?? [] as $route => $action) {
            $pattern = preg_replace('#\{[\w]+\}#', '([\w-]+)', $route);
            if (preg_match("#^$pattern$#", $uri, $matches)) {
                array_shift($matches);
                return $this->resolve($action, $matches);
            }
        }

        http_response_code(404);
        echo "404 Page Not Found";
    }

    private function resolve($action, array $params): void
    {
        if (is_callable($action)) {
            echo call_user_func_array($action, $params);
            return;
        }

        [$class, $method] = $action;
        echo call_user_func_array([new $class, $method], $params);
    }
}
