<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Akun Test
    $akun_valid = "admin@gmail.com";
    $password_valid = "admin";

    // Cek Validation
    if ($email === $akun_valid && $password === $password_valid) {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'Admin';
        
        // Go to Dashboard
        header("Location: ../index.php");
        exit();
    } else {
        // Akun tidak valid
        header("Location: Login.php?page=login&error=1");
        exit();
    }
} else {
    // Jika ada yang mencoba mengakses file ini langsung tanpa POST
    header("Location: Login.php");
    exit();
}
