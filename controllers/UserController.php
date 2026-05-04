<?php
session_start();
require_once '../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();
$user = new \App\Models\User($db);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action == 'create_staff') {
        $user->nama = $_POST['nama'];
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];
        $user->role = 'staff';

        if ($user->register()) {
            header("Location: ../index.php?page=akun-staff&success=added");
        } else {
            header("Location: ../index.php?page=akun-staff&error=add_failed");
        }
        exit();
    }
}
?>
