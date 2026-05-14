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

    // ================== AMBIL DATA ==================
    $nama_panggilan = $_POST['nama_panggilan'] ?? null;
    $nomor_telepon  = $_POST['nomor_telepon'] ?? null;
    $tanggal_lahir  = $_POST['tanggal_lahir'] ?? null;
    $jenis_kelamin  = $_POST['jenis_kelamin'] ?? null;

    // ================== VALIDASI ==================
    if ($nomor_telepon !== null) {
        $nomor_telepon = trim($nomor_telepon);
        if (!empty($nomor_telepon)) {
            // Cek apakah hanya angka dan panjang antara 11-13
            if (!ctype_digit($nomor_telepon) || strlen($nomor_telepon) < 11 || strlen($nomor_telepon) > 13) {
                header("Location: ../index.php?page=detail-profil-edit&error=invalid_phone");
                exit();
            }
        }
    }

    // ================== HANDLE KOSONG ==================
    $nama_panggilan = trim($nama_panggilan ?? '') !== ''
        ? trim($nama_panggilan)
        : null;

    $nomor_telepon = trim($nomor_telepon ?? '') !== ''
        ? trim($nomor_telepon)
        : null;

    $jenis_kelamin = trim($jenis_kelamin ?? '') !== ''
        ? trim($jenis_kelamin)
        : null;

    $tanggal_lahir = !empty($tanggal_lahir)
        ? $tanggal_lahir
        : null;

    // ================== AMBIL DATA LAMA ==================
    $query = "SELECT * FROM detail_profil WHERE id_pengguna = :id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $userId);
    $stmt->execute();

    $profil = $stmt->fetch(PDO::FETCH_ASSOC);

    // ================== FOTO ==================
    $foto_nama = $profil['foto_profil'] ?? null;

    if (!empty($_FILES['foto_profil']['name'])) {

        $targetDir = "../uploads/";

        // buat folder kalau belum ada
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES["foto_profil"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $targetFile)) {
            $foto_nama = $fileName;
        }
    }

    // ================== QUERY ==================
    if ($profil) {

        // UPDATE
        $query = "UPDATE detail_profil SET
                    nama_panggilan = :nama,
                    nomor_telepon  = :telepon,
                    tanggal_lahir  = :tgl,
                    jenis_kelamin  = :jk,
                    foto_profil    = :foto
                  WHERE id_pengguna = :id";

    } else {

        // INSERT
        $query = "INSERT INTO detail_profil
                    (
                        id_pengguna,
                        nama_panggilan,
                        nomor_telepon,
                        tanggal_lahir,
                        jenis_kelamin,
                        foto_profil
                    )
                  VALUES
                    (
                        :id,
                        :nama,
                        :telepon,
                        :tgl,
                        :jk,
                        :foto
                    )";
    }

    $stmt = $db->prepare($query);

    // ================== BINDING ==================
    $stmt->bindValue(":id", $userId, PDO::PARAM_INT);

    $stmt->bindValue(
        ":nama",
        $nama_panggilan,
        $nama_panggilan !== null ? PDO::PARAM_STR : PDO::PARAM_NULL
    );

    $stmt->bindValue(
        ":telepon",
        $nomor_telepon,
        $nomor_telepon !== null ? PDO::PARAM_STR : PDO::PARAM_NULL
    );

    $stmt->bindValue(
        ":tgl",
        $tanggal_lahir,
        $tanggal_lahir !== null ? PDO::PARAM_STR : PDO::PARAM_NULL
    );

    $stmt->bindValue(
        ":jk",
        $jenis_kelamin,
        $jenis_kelamin !== null ? PDO::PARAM_STR : PDO::PARAM_NULL
    );

    $stmt->bindValue(":foto", $foto_nama);

    // ================== EKSEKUSI ==================
    if ($stmt->execute()) {

        // refresh session profil
        $query = "SELECT * FROM detail_profil WHERE id_pengguna = :id LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $userId);
        $stmt->execute();

        $_SESSION['profil'] = $stmt->fetch(PDO::FETCH_ASSOC);

        header("Location: ../index.php?page=detail-profil&success=1");
        exit();

    } else {

        header("Location: ../index.php?page=detail-profil-edit&error=1");
        exit();
    }
}