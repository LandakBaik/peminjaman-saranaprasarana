<?php
function only($roles = [])
{
    if (!isset($_SESSION['user'])) {
        header("Location: authentication/Login.php?page=login");
        exit;
    }

    // Konversi role ke lowercase agar konsisten dengan sidebar
    $userRole = isset($_SESSION['user']['role']) ? strtolower($_SESSION['user']['role']) : '';

    if (!in_array($userRole, $roles)) {
        http_response_code(403);
        die("Akses ditolak - Anda tidak memiliki izin untuk mengakses halaman ini.");
    }
}
