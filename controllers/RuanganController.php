<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Ruangan.php';

$database = new Database();
$db = $database->getConnection();
$ruangan = new Ruangan($db);

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

    // cek MIME asli (lebih aman)
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

    $allowedTipe = ['laboratorium', 'non-laboratorium'];
    $tipe = $_POST['tipe_ruangan'] ?? '';

    if (!in_array($tipe, $allowedTipe)) {
        header("Location: ../index.php?page=ruangan&error=invalid_tipe");
        exit();
    }

    // ================= CREATE =================
    if ($action == 'create') {
        $ruangan->nama_ruangan = $_POST['nama_ruangan'];
        $ruangan->kapasitas = $_POST['kapasitas'];
        $ruangan->tipe_ruangan = $tipe;
        $ruangan->id_pengguna = $_SESSION['user']["id"] ?? null;

        $upload = uploadFoto($_FILES['foto_ruangan']);
        if (!$upload) {
            header("Location: ../index.php?page=ruangan&error=upload_failed");
            exit();
        }

        $ruangan->foto_ruangan = $upload;

        if ($ruangan->create()) {
            header("Location: ../index.php?page=ruangan&success=added");
        } else {
            header("Location: ../index.php?page=ruangan&error=add_failed");
        }
        exit();
    }

    // ================= UPDATE =================
    elseif ($action == 'update') {
        $ruangan->id_ruangan = $_POST['id_ruangan'];
        $ruangan->nama_ruangan = $_POST['nama_ruangan'];
        $ruangan->kapasitas = $_POST['kapasitas'];
        $ruangan->tipe_ruangan = $tipe;
        $ruangan->id_pengguna = $_SESSION['user']['id'] ?? null;

        if (!empty($_FILES['foto_ruangan']['name'])) {

            // hapus foto lama
            if (!empty($_POST['foto_lama']) && file_exists("../" . $_POST['foto_lama'])) {
                unlink("../" . $_POST['foto_lama']);
            }

            $upload = uploadFoto($_FILES['foto_ruangan']);
            if (!$upload) {
                header("Location: ../index.php?page=ruangan&error=upload_failed");
                exit();
            }

            $ruangan->foto_ruangan = $upload;
        } else {
            $ruangan->foto_ruangan = $_POST['foto_lama'];
        }

        if ($ruangan->update()) {
            header("Location: ../index.php?page=ruangan&success=updated");
        } else {
            header("Location: ../index.php?page=ruangan&error=update_failed");
        }
        exit();
    }
}

// ================= DELETE =================
elseif ($action == 'delete') {

    $ruangan->id_ruangan = $_GET['id_ruangan'];

    // ambil data dulu untuk hapus file
    $data = $ruangan->getById($ruangan->id_ruangan);

    if ($data && file_exists("../" . $data['foto_ruangan'])) {
        unlink("../" . $data['foto_ruangan']);
    }

    if ($ruangan->delete()) {
        header("Location: ../index.php?page=ruangan&success=deleted");
    } else {
        header("Location: ../index.php?page=ruangan&error=delete_failed");
    }
    exit();
}
