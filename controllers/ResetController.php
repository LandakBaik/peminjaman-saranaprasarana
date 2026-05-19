<?php
require_once '../config/Autoloader.php';

// Koneksi database
$database = new \App\Config\Database();
$db = $database->getConnection();

$user = new \App\Models\User($db);

// Ambil input
$email = $_POST['email'];

$password = $_POST['password'];

$confirm_password = $_POST['confirm_password'];

// Validasi password cocok
if ($password !== $confirm_password) {

    header("Location: ../authentication/Login.php?page=reset&error=not_match");

    exit();
}

// Validasi panjang password
if (strlen($password) < 6) {

    header("Location: ../authentication/Login.php?page=reset&error=password_too_short");

    exit();
}

// Validasi karakter password
if (!ctype_alnum($password)) {

    header("Location: ../authentication/Login.php?page=reset&error=password_not_alnum");

    exit();
}

// Update password
if ($user->updatePasswordByEmail($email, $password)) {

    // Hapus token reset
    $user->deleteToken($email);

    header("Location: ../authentication/Login.php?page=login&success=reset");

} else {

    echo "Gagal reset password";
}