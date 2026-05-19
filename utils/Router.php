<?php

namespace App\Utils;

class Router
{
    // Menyimpan semua route
    private static $routes = [];

    // Route default
    private static $defaultRoute = [
        'App\Controllers\DashboardController',
        'index'
    ];

    // Register route GET
    public static function get($path, $action, $middleware = [])
    {
        self::$routes[$path] = [
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    // Menjalankan route berdasarkan halaman
    public static function dispatch($page)
    {
        $page = $page ?: 'dashboard';

        // Jika route ditemukan
        if (isset(self::$routes[$page])) {

            $route = self::$routes[$page];

            // Middleware / role checking
            if (!empty($route['middleware'])) {
                if (function_exists('only')) {
                    only($route['middleware']);
                }
            }

            // Jalankan controller / file
            self::executeAction($route['action']);

        } else {

            // Route default
            if ($page === 'dashboard') {
                self::executeAction(self::$defaultRoute);
            } else {
                self::notFound();
            }
        }
    }

    // Menjalankan controller method / include file
    private static function executeAction($action)
    {
        // Jika action berupa controller
        if (is_array($action)) {

            $controllerClass = $action[0];
            $method = $action[1];

            // Cek controller ada
            if (class_exists($controllerClass)) {

                $controller = new $controllerClass();

                // Cek method ada
                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    self::notFound();
                }

            } else {
                self::notFound();
            }

        // Jika action berupa file
        } elseif (is_string($action) && file_exists($action)) {

            include $action;

        } else {
            self::notFound();
        }
    }

    // Halaman 404
    public static function notFound()
    {
        http_response_code(404);

        if (file_exists('pages/404.php')) {
            include 'pages/404.php';
        } else {
            echo "
                <div class='container-fluid px-4 mt-4'>
                    <h1>404 - Halaman Tidak Ditemukan</h1>
                    <p>Maaf, halaman yang Anda cari tidak tersedia.</p>
                </div>
            ";
        }
    }
}