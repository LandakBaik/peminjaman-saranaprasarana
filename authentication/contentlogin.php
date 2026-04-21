<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Akun Test
    $users = [
        [
            "email" => "admin@gmail.com",
            "password" => "1234",
            "role" => "admin"
        ],
        [
            "email" => "rolan@gmail.com",
            "password" => "1234",
            "role" => "user"
        ]
    ];
    
    $loginberhasil = false;
    // Cek Validation
    foreach ($users as $user) {
        if ($email === $user['email'] && $password === $user['password']) {

            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role'];
        
            $loginberhasil = true;
            if ($user['role'] == 'admin') {
                header("Location: ../index.php");
            } elseif ($user['role'] == 'user') {
                header("Location: ../pages/layout-sidenav-light.php");
            } else {
                header("Location: Login.php?page=login&error=1");
            }
            
            exit();
        }
    }

    if (!$loginberhasil) {
        header("Location: Login.php?page=login&error=1");
        exit();
    }
} else {
    // akun tidak valid
    header("Location: Login.php?page=login&error=1");
    exit();
}
