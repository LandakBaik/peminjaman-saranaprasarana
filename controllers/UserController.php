++<?php
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

            // Assign ruangan jika ada yang dipilih
            if (isset($_POST['ruangan_ids']) && is_array($_POST['ruangan_ids']) && count($_POST['ruangan_ids']) > 0) {
                $ruangan = new \App\Models\Ruangan($db);
                $ruangan->assignStaffToRooms($user->id_pengguna, $_POST['ruangan_ids']);
            }

            header("Location: ../index.php?page=akun-staff&success=added");
        } else {
            header("Location: ../index.php?page=akun-staff&error=add_failed");
        }
        exit();
    }
}
?>
