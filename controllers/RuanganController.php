<?php
session_start();
require_once '../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();
$ruangan = new \App\Models\Ruangan($db);

$action = $_GET['action'] ?? '';

// ================= FUNCTION UPLOAD =================
function uploadFoto($file)
{
    $targetDir = "../uploads/";

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $allowedExt = ['jpg', 'jpeg', 'png'];
    $allowedMime = ['image/jpeg', 'image/png'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    $fileExt = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    // cek ekstensi
    if (!in_array($fileExt, $allowedExt)) {
        return false;
    }

    // cek ukuran
    if ($file["size"] > $maxSize) {
        return false;
    }

    // cek MIME asli
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file["tmp_name"]);
    finfo_close($finfo);

    if (!in_array($mime, $allowedMime)) {
        return false;
    }

    // generate nama file aman
    $fileName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $fileExt;
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return "uploads/" . $fileName;
    }

    return false;
}

// ================= REQUEST =================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    // ================= CREATE =================
    if ($action == 'create') {
        $allowedTipe = ['laboratorium', 'non-laboratorium'];
        $tipe = $_POST['tipe_ruangan'] ?? '';
        if (empty($tipe)) {
            $tipe = $_POST['tipe_lama'] ?? '';
        }
        if (!in_array($tipe, $allowedTipe)) {
            header("Location: ../index.php?page=ruangan&error=invalid_tipe");
            exit();
        }

        $ruangan->nama_ruangan = $_POST['nama_ruangan'];
        $ruangan->kapasitas = $_POST['kapasitas'];
        $ruangan->tipe_ruangan = $_POST['tipe_ruangan'];
        $ruangan->id_pengguna = $_SESSION['user']["id"] ?? null;

        // FOTO OPSIONAL
        if (!empty($_FILES['foto_ruangan']['name'])) {

            $upload = uploadFoto($_FILES['foto_ruangan']);

            if (!$upload) {
                header("Location: ../index.php?page=ruangan&error=upload_failed");
                exit();
            }

            $ruangan->foto_ruangan = $upload;

        } else {

            // jika kosong
            $ruangan->foto_ruangan = null;
        }

        if ($ruangan->create()) {
            header("Location: ../index.php?page=ruangan&success=added");
        } else {
            header("Location: ../index.php?page=ruangan&error=add_failed");
        }

        exit();
    }

    // ================= UPDATE =================
    elseif ($action == 'update') {
        $allowedTipe = ['laboratorium', 'non-laboratorium'];
        $tipe = $_POST['tipe_ruangan'] ?? '';
        if (empty($tipe)) {
            $tipe = $_POST['tipe_lama'] ?? '';
        }
        if (!in_array($tipe, $allowedTipe)) {
            header("Location: ../index.php?page=ruangan&error=invalid_tipe");
            exit();
        }

        $ruangan->id_ruangan = $_POST['id_ruangan'];
        $ruangan->nama_ruangan = $_POST['nama_ruangan'];
        $ruangan->kapasitas = $_POST['kapasitas'];
        $ruangan->tipe_ruangan = $_POST['tipe_ruangan'];
        $ruangan->id_pengguna = $_SESSION['user']['id'] ?? null;

        // jika upload foto baru
        if (!empty($_FILES['foto_ruangan']['name'])) {

            // hapus foto lama jika ada
            if (
                !empty($_POST['foto_lama']) &&
                file_exists("../" . $_POST['foto_lama'])
            ) {
                unlink("../" . $_POST['foto_lama']);
            }

            $upload = uploadFoto($_FILES['foto_ruangan']);

            if (!$upload) {
                header("Location: ../index.php?page=ruangan&error=upload_failed");
                exit();
            }

            $ruangan->foto_ruangan = $upload;

        } else {

            // pakai foto lama
            $ruangan->foto_ruangan = $_POST['foto_lama'] ?? null;
        }

        if ($ruangan->update()) {
            header("Location: ../index.php?page=ruangan&success=updated");
        } else {
            header("Location: ../index.php?page=ruangan&error=update_failed");
        }

        exit();
    }

    // ================= EXPORT =================
    elseif ($action == 'export') {
        if (!empty($_POST['id_ruangan']) && is_array($_POST['id_ruangan'])) {
            $ids = $_POST['id_ruangan'];
            $stmt = $ruangan->getByIds($ids);

            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Data_Ruangan.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>Nama Ruangan</th>";
            echo "<th>Kapasitas</th>";
            echo "<th>Tipe</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nama_ruangan']) . "</td>";
                echo "<td>" . htmlspecialchars($row['kapasitas']) . "</td>";
                echo "<td>" . htmlspecialchars(ucfirst($row['tipe_ruangan'])) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            exit();
        } else {
            header("Location: ../index.php?page=ruangan&error=no_items_selected");
            exit();
        }
    }
}

// ================= DELETE =================
elseif ($action == 'delete') {

    $ruangan->id_ruangan = $_GET['id_ruangan'];

    // ambil data dulu
    $data = $ruangan->getById($ruangan->id_ruangan);

    // hapus file jika ada
    if (
        $data &&
        !empty($data['foto_ruangan']) &&
        file_exists("../" . $data['foto_ruangan'])
    ) {
        unlink("../" . $data['foto_ruangan']);
    }

    if ($ruangan->delete()) {
        header("Location: ../index.php?page=ruangan&success=deleted");
    } else {
        header("Location: ../index.php?page=ruangan&error=delete_failed");
    }

    exit();
}