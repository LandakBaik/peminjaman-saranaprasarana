<?php
require_once '../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();
$user = new \App\Models\User($db);

$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if ($password !== $confirm_password) {
    header("Location: ../authentication/Login.php?page=reset&error=not_match");
    exit();
}

if ($user->updatePasswordByEmail($email, $password)) {

    // hapus token
    $user->deleteToken($email);

    header("Location: ../authentication/Login.php?page=login&success=reset");
} else {
    echo "Gagal reset password";
}
