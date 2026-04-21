<?php
session_start();
require '../data/users.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    foreach ($users as $user) {
        if ($email === $user['email'] && $password === $user['password']) {

            $_SESSION['user'] = [
                'email' => $user['email'],
                'role' => $user['role']
            ];

            header("Location: ../index.php");
            exit();
        }
    }

    header("Location: Login.php?page=login&error=1");
    exit();
}
