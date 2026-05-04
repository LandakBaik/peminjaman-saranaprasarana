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

        $barang->id_barang = $_POST['id_barang']; // kalau pakai custom ID
        $barang->id_ruangan = $_POST['id_ruangan'];
        $barang->nama_barang = $_POST['nama_barang'];
        $barang->deskripsi_barang = $_POST['deskripsi_barang'];
        $barang->total_stok = $_POST['total_stok'];
        $barang->stok_rusak = 0; // default

        if ($barang->create()) {
            header("Location: ../index.php?page=barang&success=added");
        } else {
            header("Location: ../index.php?page=barang&error=add_failed");
        }
        exit();
    }

    // ================= UPDATE =================
    elseif ($action == 'update') {

        $barang->id_barang = $_POST['id_barang'];
        $barang->id_ruangan = $_POST['id_ruangan'];
        $barang->nama_barang = $_POST['nama_barang'];
        $barang->deskripsi_barang = $_POST['deskripsi_barang'];
        $barang->total_stok = $_POST['total_stok'];
        $barang->stok_rusak = $_POST['stok_rusak'] ?? 0;

        if ($barang->update()) {
            header("Location: ../index.php?page=barang&success=updated");
        } else {
            header("Location: ../index.php?page=barang&error=update_failed");
        }
        exit();
    }
}

// ================= DELETE =================
elseif ($action == 'delete') {

    $barang->id_barang = $_GET['id_barang'];

    if ($barang->delete()) {
        header("Location: ../index.php?page=barang&success=deleted");
    } else {
        header("Location: ../index.php?page=barang&error=delete_failed");
    }
    exit();
}
?>