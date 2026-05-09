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

    // ================== AMBIL DATA (AMAN) ==================
    $nama_panggilan  = $_POST['nama_panggilan'] ?? '';
    $nomor_telepon   = $_POST['nomor_telepon'] ?? '';
    $tanggal_lahir   = $_POST['tanggal_lahir'] ?? null;
    $jenis_kelamin   = $_POST['jenis_kelamin'] ?? null;

    // handle tanggal kosong → NULL (biar tidak error di MySQL)
    if (empty($tanggal_lahir)) {
        $tanggal_lahir = null;
    }

    // ================== VALIDASI SEDERHANA ==================
    if (!$nama_panggilan || !$nomor_telepon) {
        header("Location: ../index.php?page=detail-profil-edit&error=required");
        exit();
    }

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
                    nama_panggilan  = :nama,
                    nomor_telepon   = :telepon,
                    tanggal_lahir   = :tgl,
                    jenis_kelamin   = :jk,
                    foto_profil     = :foto
                  WHERE id_pengguna = :id";
    } else {
        // INSERT
        $query = "INSERT INTO detail_profil 
                  (id_pengguna, nama_panggilan, nomor_telepon, tanggal_lahir, jenis_kelamin, foto_profil)
                  VALUES (:id, :nama, :telepon, :tgl, :jk, :foto)";
    }

    $stmt = $db->prepare($query);

    // ================== BINDING (AMAN) ==================
    $stmt->bindValue(":id", $userId);
    $stmt->bindValue(":nama", $nama_panggilan);
    $stmt->bindValue(":telepon", $nomor_telepon);

    // khusus tanggal (handle NULL)
    $stmt->bindValue(":tgl", $tanggal_lahir, $tanggal_lahir ? PDO::PARAM_STR : PDO::PARAM_NULL);

    $stmt->bindValue(":jk", $jenis_kelamin);
    $stmt->bindValue(":foto", $foto_nama);

    // ================== EKSEKUSI ==================
    if ($stmt->execute()) {
        header("Location: ../index.php?page=detail-profil&success=1");
        exit();
    } else {
        header("Location: ../index.php?page=detail-profil-edit&error=1");
        exit();
    }

    // ================== EKSPORT ==================
    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //     elseif ($action == 'export') {
    //     if (!empty($_POST['id_barang']) && is_array($_POST['id_barang'])) {
    //         $ids = $_POST['id_barang'];
    //         $stmt = $barang->getByIds($ids);

    //         header("Content-Type: application/vnd.ms-excel");
    //         header("Content-Disposition: attachment; filename=Data_Barang.xls");
    //         header("Pragma: no-cache");
    //         header("Expires: 0");

    //         echo "<table border='1'>";
    //         echo "<tr>";
    //         echo "<th>Nama Barang</th>";
    //         echo "<th>Deskripsi</th>";
    //         echo "<th>Ruangan</th>";
    //         echo "<th>Total</th>";
    //         echo "<th>Rusak</th>";
    //         echo "<th>Dipinjam</th>";
    //         echo "<th>Tersedia</th>";
    //         echo "</tr>";

    //         while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
    //             echo "<tr>";
    //             echo "<td>" . htmlspecialchars($row['nama_barang']) . "</td>";
    //             echo "<td>" . htmlspecialchars($row['deskripsi_barang']) . "</td>";
    //             echo "<td>" . htmlspecialchars($row['nama_ruangan'] ?? '-') . "</td>";
    //             echo "<td>" . $row['total_stok'] . "</td>";
    //             echo "<td>" . $row['stok_rusak'] . "</td>";
    //             echo "<td>" . $row['dipinjam'] . "</td>";
    //             echo "<td>" . $row['tersedia'] . "</td>";
    //             echo "</tr>";
    //         }
    //         echo "</table>";
    //         exit();
    //     } else {
    //         header("Location: ../index.php?page=barang&error=no_items_selected");
    //         exit();
    //     }
    // }    
}