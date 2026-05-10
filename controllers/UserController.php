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
        $user->role = $_POST['role'] ?? 'staff';

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
    } elseif ($action == 'update') {
        $user->id_pengguna = $_POST['id_pengguna'];
        $user->nama = $_POST['nama'];
        $user->email = $_POST['email'];
        $user->role = $_POST['role'];

        if ($user->update()) {
            $ruangan = new \App\Models\Ruangan($db);
            // Unassign dari semua ruangan dulu (set ke admin saat ini jika kolom NOT NULL)
            $adminId = $_SESSION['user']['id'];
            $ruangan->unassignStaffFromAllRooms($user->id_pengguna, $adminId);
            
            // Assign ke ruangan baru jika ada yang dipilih
            if (isset($_POST['ruangan_ids']) && is_array($_POST['ruangan_ids']) && count($_POST['ruangan_ids']) > 0) {
                $ruangan->assignStaffToRooms($user->id_pengguna, $_POST['ruangan_ids']);
            }
            header("Location: ../index.php?page=akun-staff&success=updated");
        } else {
            header("Location: ../index.php?page=akun-staff&error=update_failed");
        }
        exit();
    } elseif ($action == 'export') {
        if (!empty($_POST['id_user']) && is_array($_POST['id_user'])) {
            $ids = $_POST['id_user'];
            $stmt = $user->getByIds($ids);

            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Data_Akun.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>ID Pengguna</th>";
            echo "<th>Nama</th>";
            echo "<th>Email</th>";
            echo "<th>Role</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id_pengguna'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row['nama'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row['email'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row['role'] ?? '') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            exit();
        } else {
            header("Location: ../index.php?page=daftar-akun&error=no_items_selected");
            exit();
        }
    }
}
?>
