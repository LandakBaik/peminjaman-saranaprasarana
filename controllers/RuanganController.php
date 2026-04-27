<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Ruangan.php';

$database = new Database();
$db = $database->getConnection();
$ruangan = new Ruangan($db);

$action = $_GET['action'] ?? '';

// fungsi upload
function uploadFoto($file)
{
    $targetDir = "../uploads/";

    // buat folder jika belum ada
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . '_' . basename($file["name"]);
    $targetFile = $targetDir . $fileName;

    // validasi tipe file
    $allowedTypes = ['jpg', 'jpeg', 'png'];
    $fileExt = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedTypes)) {
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $fileName;
    }

    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // validasi ENUM tipe_ruangan
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
        $ruangan->id_pengguna = $_SESSION['user_id'] ?? null;

        // upload foto
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
        $ruangan->id_pengguna = $_SESSION['user_id'] ?? null;

        // cek apakah upload foto baru
        if (!empty($_FILES['foto_ruangan']['name'])) {
            $upload = uploadFoto($_FILES['foto_ruangan']);
            if (!$upload) {
                header("Location: ../index.php?page=ruangan&error=upload_failed");
                exit();
            }
            $ruangan->foto_ruangan = $upload;
        } else {
            // tetap pakai foto lama
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

    if ($ruangan->delete()) {
        header("Location: ../index.php?page=ruangan&success=deleted");
    } else {
        header("Location: ../index.php?page=ruangan&error=delete_failed");
    }
    exit();
}


