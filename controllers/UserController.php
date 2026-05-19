<?php
session_start();

require_once '../config/Autoloader.php';

// Koneksi database
$database = new \App\Config\Database();
$db = $database->getConnection();

$user = new \App\Models\User($db);

$action = isset($_GET['action'])
    ? $_GET['action']
    : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Create staff
    if ($action == 'create_staff') {

        $user->nama =
            $_POST['nama'];

        $user->email =
            $_POST['email'];

        $user->password =
            $_POST['password'];

        $user->role =
            $_POST['role'] ?? 'staff';

        // Validasi password
        if (strlen($user->password) < 6) {

            header("Location: ../index.php?page=akun-staff&error=password_too_short");

            exit();
        }

        // Validasi karakter password
        if (!ctype_alnum($user->password)) {

            header("Location: ../index.php?page=akun-staff&error=password_not_alnum");

            exit();
        }

        // Simpan staff
        if ($user->register()) {

            // Assign ruangan staff
            if (
                isset($_POST['ruangan_ids']) &&
                is_array($_POST['ruangan_ids']) &&
                count($_POST['ruangan_ids']) > 0
            ) {

                $ruangan = new \App\Models\Ruangan($db);

                $ruangan->assignStaffToRooms(
                    $user->id_pengguna,
                    $_POST['ruangan_ids']
                );
            }

            header("Location: ../index.php?page=akun-staff&success=added");

        } else {

            header("Location: ../index.php?page=akun-staff&error=add_failed");
        }

        exit();
    }

    // Update akun
    elseif ($action == 'update') {

        $user->id_pengguna =
            $_POST['id_pengguna'];

        $user->nama =
            $_POST['nama'];

        $user->email =
            $_POST['email'];

        $user->role =
            $_POST['role'];

        $user->status =
            $_POST['status'] ?? 'aktif';

        // Update data akun
        if ($user->update()) {

            $ruangan = new \App\Models\Ruangan($db);

            $adminId =
                $_SESSION['user']['id'];

            // Reset assign ruangan lama
            $ruangan->unassignStaffFromAllRooms(
                $user->id_pengguna,
                $adminId
            );

            // Assign ruangan baru
            if (
                isset($_POST['ruangan_ids']) &&
                is_array($_POST['ruangan_ids']) &&
                count($_POST['ruangan_ids']) > 0
            ) {

                $ruangan->assignStaffToRooms(
                    $user->id_pengguna,
                    $_POST['ruangan_ids']
                );
            }

            header("Location: ../index.php?page=akun-staff&success=updated");

        } else {

            header("Location: ../index.php?page=akun-staff&error=update_failed");
        }

        exit();
    }

    // Export akun
    elseif ($action == 'export') {

        if (
            !empty($_POST['id_user']) &&
            is_array($_POST['id_user'])
        ) {

            $ids = $_POST['id_user'];

            $stmt = $user->getByIds($ids);

            // Header export excel
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
            echo "<th>Status</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

                echo "<tr>";

                echo "<td>" . htmlspecialchars($row['id_pengguna'] ?? '') . "</td>";

                echo "<td>" . htmlspecialchars($row['nama'] ?? '') . "</td>";

                echo "<td>" . htmlspecialchars($row['email'] ?? '') . "</td>";

                echo "<td>" . htmlspecialchars($row['role'] ?? '') . "</td>";

                echo "<td>" . htmlspecialchars($row['status'] ?? '') . "</td>";

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

// Update status akun
if (
    $_SERVER['REQUEST_METHOD'] === 'GET' &&
    $action == 'update_status'
) {

    $id = $_GET['id'];

    $status = $_GET['status'];

    $query = "
        UPDATE pengguna 
        SET status = :status 
        WHERE id_pengguna = :id
    ";

    $stmt = $db->prepare($query);

    $stmt->bindParam(":status", $status);

    $stmt->bindParam(":id", $id);

    if ($stmt->execute()) {

        header("Location: ../index.php?page=akun-staff&success=status_updated");

    } else {

        header("Location: ../index.php?page=akun-staff&error=status_update_failed");
    }

    exit();
}
?>