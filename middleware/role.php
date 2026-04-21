<?php
function only($roles = [])
{
    if (!isset($_SESSION['user'])) {
        header("Location: auth/Login.php?page=login");
        exit;
    }

    $userRole = $_SESSION['user']['role'];

    if (!in_array($userRole, $roles)) {
        die("Akses ditolak");
    }
}
