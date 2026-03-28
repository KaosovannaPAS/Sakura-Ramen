<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function add($method, $path, $handler)
    {
        $path = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $path);
        $this->routes[] = [
            'method' => $method,
            'path' => '#^' . $path . '$#',
            'handler' => $handler
        ];
    }

    public function dispatch($method, $uri)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $uri, $matches)) {
                $handler = $route['handler'];
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                if (is_callable($handler)) {
                    return call_user_func_array($handler, $params);
                }

                if (is_array($handler)) {
                    [$controllerName, $action] = $handler;
                    $controller = new $controllerName();
                    return call_user_func_array([$controller, $action], $params);
                }
            }
        }

        $this->abort(404);
    }

    private function abort($code = 404)
    {
        http_response_code($code);
        echo "Error $code - Page not found";
        exit;
    }
}
