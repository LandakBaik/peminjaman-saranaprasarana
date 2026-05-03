<?php
require_once '../config/Database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

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
