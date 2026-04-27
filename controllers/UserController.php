<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action == 'create_staff') {
        $user->nama = $_POST['name'];
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
