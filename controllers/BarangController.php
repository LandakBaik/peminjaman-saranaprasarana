<?php
session_start();
require_once '../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();
$barang = new \App\Models\Barang($db);

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ================= CREATE =================
    if ($action == 'create') {

        $barang->id_barang = $_POST['id_barang'] ?? null;
        $barang->id_ruangan = $_POST['id_ruangan'];
        $barang->nama_barang = $_POST['nama_barang'];
        $barang->deskripsi_barang = $_POST['deskripsi_barang'];
        $barang->total_stok = $_POST['total_stok'];
        $barang->stok_rusak = 0; // default

        if ($barang->create()) {
            if (isset($_POST['source']) && $_POST['source'] == 'ruangan') {
                header("Location: ../index.php?page=ruangan-barang&success=added");
            } else {
                header("Location: ../index.php?page=ruangan-barang&success=added");
            }
        } else {
            header("Location: ../index.php?page=ruangan-barang&error=add_failed");
        }
        exit();
    }

    // ================= UPDATE =================
    elseif ($action == 'update') {

        $barang->id_barang = $_POST['id_barang'];
        $barang->id_ruangan = $_POST['id_ruangan'];
        $barang->nama_barang = $_POST['nama_barang'];
        $barang->deskripsi_barang = $_POST['deskripsi_barang'];
        $barang->total_stok = (int)$_POST['total_stok'];
        $barang->stok_rusak = (int)($_POST['stok_rusak'] ?? 0);

        // VALIDASI: Stok rusak tidak boleh melebihi total stok
        if ($barang->stok_rusak > $barang->total_stok) {
            header("Location: ../index.php?page=ruangan-barang&error=stok_invalid");
            exit();
        }

        if ($barang->update()) {
            if (isset($_POST['source']) && $_POST['source'] == 'ruangan') {
                header("Location: ../index.php?page=ruangan-barang&success=updated");
            } else {
                header("Location: ../index.php?page=ruangan-barang&success=updated");
            }
        } else {
            header("Location: ../index.php?page=ruangan-barang&error=update_failed");
        }
        exit();
    }


    // ================= EXPORT =================
    elseif ($action == 'export') {
        if (!empty($_POST['id_barang']) && is_array($_POST['id_barang'])) {
            $ids = $_POST['id_barang'];
            $stmt = $barang->getByIds($ids);

            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Data_Barang.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>Nama Barang</th>";
            echo "<th>Deskripsi</th>";
            echo "<th>Ruangan</th>";
            echo "<th>Total</th>";
            echo "<th>Rusak</th>";
            echo "<th>Dipinjam</th>";
            echo "<th>Tersedia</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nama_barang']) . "</td>";
                echo "<td>" . htmlspecialchars($row['deskripsi_barang']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nama_ruangan'] ?? '-') . "</td>";
                echo "<td>" . $row['total_stok'] . "</td>";
                echo "<td>" . $row['stok_rusak'] . "</td>";
                echo "<td>" . $row['dipinjam'] . "</td>";
                echo "<td>" . $row['tersedia'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            exit();
        } else {
            header("Location: ../index.php?page=ruangan-barang&error=no_items_selected");
            exit();
        }
    }
}

// ================= DELETE =================
elseif ($action == 'delete') {

    $barang->id_barang = $_GET['id_barang'];

    if ($barang->delete()) {
        // Cek jika request dari halaman ruangan (Master-Detail)
        if (isset($_GET['source']) && $_GET['source'] == 'ruangan') {
            header("Location: ../index.php?page=ruangan-barang&success=deleted");
        } else {
            header("Location: ../index.php?page=ruangan-barang&success=deleted");
        }
    } else {
        header("Location: ../index.php?page=ruangan-barang&error=delete_failed");
    }
    exit();
}

// ================= AJAX: LIST BY RUANGAN =================
elseif ($action == 'list_by_ruangan') {
    header('Content-Type: application/json');
    $id_ruangan = $_GET['id_ruangan'] ?? 0;
    
    // Gunakan method existing yang sudah ada di model Barang
    $stmt = $barang->getByRuangan($id_ruangan);
    
    echo json_encode($stmt);
    exit();
}
?>