<?php
/**
 * Простой роутер для приложения
 */

class Router
{
    private static $routes = [];

    public static function get($path, $callback)
    {
        self::$routes['GET'][self::normalizePath($path)] = $callback;
    }

    public static function post($path, $callback)
    {
        self::$routes['POST'][self::normalizePath($path)] = $callback;
    }

    public static function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Удаляем префикс /public если присутствует
        $path = str_replace('/public', '', $path);
        
        $path = self::normalizePath($path);

        // Попытка найти точное совпадение
        if (isset(self::$routes[$method][$path])) {
            call_user_func(self::$routes[$method][$path]);
            return;
        }

        // Попытка найти с параметрами
        foreach (self::$routes[$method] ?? [] as $pattern => $callback) {
            $pattern = str_replace('{id}', '(\d+)', $pattern);
            $pattern = str_replace('{slug}', '([a-z0-9-]+)', $pattern);
            
            if (preg_match("#^$pattern$#", $path, $matches)) {
                array_shift($matches);
                call_user_func_array($callback, $matches);
                return;
            }
        }

        // Маршрут не найден
        http_response_code(404);
        echo '404 - Страница не найдена';
    }

    private static function normalizePath($path)
    {
        return '/' . trim($path, '/');
    }
}
