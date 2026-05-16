<?php

namespace App\Utils;

class Router
{
    private static $routes = [];
    private static $defaultRoute = ['App\Controllers\DashboardController', 'index'];

    /**
     * Register a GET route
     * 
     * @param string $path The route name (e.g., 'dashboard')
     * @param array|string $action The controller action array [ControllerClass, 'method'] or file path
     * @param array $middleware Array of roles allowed to access
     */
    public static function get($path, $action, $middleware = [])
    {
        self::$routes[$path] = [
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    /**
     * Dispatch the current request
     * 
     * @param string $page The current page identifier from query param or URI
     */
    public static function dispatch($page)
    {
        // Sanitize page input
        $page = $page ?: 'dashboard';

        if (isset(self::$routes[$page])) {
            $route = self::$routes[$page];

            // Handle middleware (roles)
            if (!empty($route['middleware'])) {
                if (function_exists('only')) {
                    only($route['middleware']);
                }
            }

            // Execute action
            self::executeAction($route['action']);
        } else {
            // Default behavior if route not found
            if ($page === 'dashboard') {
                self::executeAction(self::$defaultRoute);
            } else {
                self::notFound();
            }
        }
    }

    private static function executeAction($action)
    {
        if (is_array($action)) {
            $controllerClass = $action[0];
            $method = $action[1];
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    self::notFound();
                }
            } else {
                self::notFound();
            }
        } elseif (is_string($action) && file_exists($action)) {
            include $action;
        } else {
            self::notFound();
        }
    }

    public static function notFound()
    {
        http_response_code(404);
        if (file_exists('pages/404.php')) {
            include 'pages/404.php';
        } else {
            echo "<div class='container-fluid px-4 mt-4'><h1>404 - Halaman Tidak Ditemukan</h1><p>Maaf, halaman yang Anda cari tidak tersedia.</p></div>";
        }
    }
}
