<?php
session_start();
require_once '../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();

$userId = $_SESSION['user']['id'] ?? 0;

$action = $_GET['action'] ?? '';

// ================== GET DATA PROFIL ==================
if ($action == 'get') {

    $query = "SELECT * FROM detail_profil WHERE id_pengguna = :id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $userId);
    $stmt->execute();

    $profil = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profil) {
        $profil = [];
    }

    $_SESSION['profil'] = $profil;

    header("Location: ../index.php?page=detail-profil");
    exit();
}


// ================== UPDATE / INSERT ==================
if ($action == 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_panggilan  = $_POST['nama_panggilan'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    // ambil data lama
    $query = "SELECT * FROM detail_profil WHERE id_pengguna = :id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $userId);
    $stmt->execute();

    $profil = $stmt->fetch(PDO::FETCH_ASSOC);

    // ================== FOTO ==================
    $foto_nama = $profil['foto_profil'] ?? null;

    if (!empty($_FILES['foto_profil']['name'])) {

        $targetDir = "../uploads/";
        $fileName = time() . "_" . $_FILES["foto_profil"]["name"];
        $targetFile = $targetDir . $fileName;

        move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $targetFile);
        $foto_nama = $fileName;
    }

    // ================== INSERT / UPDATE ==================
    if ($profil) {
        // UPDATE
        $query = "UPDATE detail_profil SET
                    nama_panggilan  = :nama,
                    nomor_telepon = :telepon,
                    tanggal_lahir = :tgl,
                    jenis_kelamin = :jk,
                    foto_profil   = :foto
                  WHERE id_pengguna = :id";
    } else {
        // INSERT
        $query = "INSERT INTO detail_profil 
                  (id_pengguna, nama_panggilan, nomor_telepon, tanggal_lahir, jenis_kelamin, foto_profil)
                  VALUES (:id, :nama, :telepon, :tgl, :jk, :foto)";
    }

    $stmt = $db->prepare($query);

    $stmt->bindParam(":id", $userId);
    $stmt->bindParam(":nama", $nama_panggilan);
    $stmt->bindParam(":telepon", $nomor_telepon);
    $stmt->bindParam(":tgl", $tanggal_lahir);
    $stmt->bindParam(":jk", $jenis_kelamin);
    $stmt->bindParam(":foto", $foto_nama);

    if ($stmt->execute()) {
        header("Location: ../index.php?page=detail-profil&success=1");
        exit();
    } else {
        header("Location: ../index.php?page=detail-profil-edit&error=1");
        exit();
    }
}