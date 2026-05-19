<?php

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Load Composer autoload
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Autoload class project
spl_autoload_register(function ($class) {

    $prefixes = [
        'App\\Config\\' => __DIR__ . '/',
        'App\\Models\\' => __DIR__ . '/../models/',
        'App\\Controllers\\' => __DIR__ . '/../controllers/',
        'App\\Utils\\' => __DIR__ . '/../utils/',
    ];

    foreach ($prefixes as $prefix => $base_dir) {

        $len = strlen($prefix);

        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relative_class = substr($class, $len);

        $file = $base_dir .
            str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) .
            '.php';

        // Load file class
        if (file_exists($file)) {
            require $file;
        }

        return;
    }
});

// Alias class lama
$classAliases = [
    'Database' => App\Config\Database::class,
    'Barang' => App\Models\Barang::class,
    'Peminjaman' => App\Models\Peminjaman::class,
    'Ruangan' => App\Models\Ruangan::class,
    'User' => App\Models\User::class,
];

foreach ($classAliases as $alias => $target) {

    // Buat alias jika belum ada
    if (
        !class_exists($alias, false) &&
        !interface_exists($alias, false) &&
        !trait_exists($alias, false)
    ) {
        class_alias($target, $alias);
    }
}

?>