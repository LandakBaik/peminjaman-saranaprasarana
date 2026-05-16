<?php
session_start();
require_once '../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();
$user = new \App\Models\User($db);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'login') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($user->login($email, $password)) {
        $_SESSION['user'] = [
            'id' => $user->id_pengguna,
            'nama' => $user->nama,
            'email' => $email,
            'role' => $user->role
        ];
        header("Location: ../index.php");
        exit();
    } else {
        header("Location: ../authentication/Login.php?page=login&error=1");
        exit();
    }
} elseif ($action == 'register') {
    $user->nama = $_POST['nama'] ?? '';
    $user->email = $_POST['email'] ?? '';
    $user->password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user->role = 'user'; // default role

    if ($user->password !== $confirm_password) {
        header("Location: ../authentication/Login.php?page=register&error=1");
        exit();
    }
    
    if (strlen($user->password) < 6) {
        header("Location: ../authentication/Login.php?page=register&error=password_too_short");
        exit();
    }

    if (!ctype_alnum($user->password)) {
        header("Location: ../authentication/Login.php?page=register&error=password_not_alnum");
        exit();
    }
    if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../authentication/Login.php?page=register&error=invalid_email");
        exit();
    }
    
    $domain = strtolower(substr(strrchr($user->email, "@"), 1));

    //domain email 
    if ($domain !== "student.polije.ac.id") {
        header("Location: ../authentication/Login.php?page=register&error=domain_invalid");
        exit();
    }

    if ($user->register()) {
        header("Location: ../authentication/Login.php?page=login&success=1");
        exit();
    } else {
        header("Location: ../authentication/Login.php?page=register&error=email_exists");
        exit();
    }
} elseif ($action == 'logout') {
    session_destroy();
    header("Location: ../authentication/Login.php");
    exit();
}
