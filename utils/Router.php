<?php

namespace App\Utils;

class Router
{
    private static $routes = [];
    private static $defaultRoute = 'pages/dashboard.php';

    /**
     * Register a GET route
     * 
     * @param string $path The route name (e.g., 'dashboard')
     * @param string $file The file to include
     * @param array $middleware Array of roles allowed to access
     */
    public static function get($path, $file, $middleware = [])
    {
        self::$routes[$path] = [
            'file' => $file,
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

            // Include the page file
            if (file_exists($route['file'])) {
                include $route['file'];
            } else {
                self::notFound();
            }
        } else {
            // Default behavior if route not found
            if ($page === 'dashboard') {
                include self::$defaultRoute;
            } else {
                self::notFound();
            }
        }
    }

    private static function notFound()
    {
        http_response_code(404);
        echo "<div class='container-fluid px-4 mt-4'><h1>404 - Halaman Tidak Ditemukan</h1><p>Maaf, halaman yang Anda cari tidak tersedia.</p></div>";
    }
}
