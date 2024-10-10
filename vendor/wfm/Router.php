<?php

namespace wfm;

class Router
{
    protected static array $routes = [];
    protected static array $route = [];

    public static function add(string $regexp, array $route = []): void
    {
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function getRoute(): array
    {
        return self::$route;
    }

    protected static function removeQueryString(string $url): string
    {
        $url = strtok($url, '?'); // Удаление всего после ?
        return rtrim($url, '/');
    }

    public static function dispatch(string $url): void
    {
        $url = self::removeQueryString($url);
        try {
            if (self::matchRoute($url)) {
                $controller = 'app\controllers\\' . self::$route['admin_prefix'] . self::$route['controller'] . 'Controller';
                if (class_exists($controller)) {
                    $controllerObject = new $controller(self::$route);
                    $controllerObject->getModel();

                    $action = self::lowerCamelCase(self::$route['action'] . 'Action');
                    if (method_exists($controllerObject, $action)) {
                        $controllerObject->$action();
                        $controllerObject->getView();
                    } else {
                        throw new \Exception("Метод {$controller}::{$action} не найден", 404);
                    }
                } else {
                    throw new \Exception("Контроллер {$controller} не найден", 404);
                }
            } else {
                throw new \Exception("Страница не найдена", 404);
            }
        } catch (\Exception $e) {
            http_response_code($e->getCode()); // Устанавливаем код ответа
            if ($e->getCode() === 404) {
                require 'views/404.php'; // Подключаем страницу 404
            } else {
                // Обрабатываем другие ошибки
                echo 'Произошла ошибка: ' . $e->getMessage();
            }
        }
    }

    public static function matchRoute(string $url): bool
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#{$pattern}#", $url, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $route[$k] = $v;
                    }
                }
                if (empty($route['action'])) {
                    $route['action'] = 'index';
                }
                $route['admin_prefix'] = $route['admin_prefix'] ?? '';
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    protected static function upperCamelCase(string $name): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }

    protected static function lowerCamelCase(string $name): string
    {
        return lcfirst(self::upperCamelCase($name));
    }
}
