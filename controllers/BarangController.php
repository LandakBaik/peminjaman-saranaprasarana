<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Barang.php';

$database = new Database();
$db = $database->getConnection();
$barang = new Barang($db);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action == 'create') {
        $barang->nama_barang = $_POST['nama_barang'];
        $barang->total = $_POST['total'];
        $barang->rusak = 0;
        $barang->dipinjam = 0;

        if ($barang->create()) {
            header("Location: ../index.php?page=barang&success=added");
        } else {
            header("Location: ../index.php?page=barang&error=add_failed");
        }
        exit();
    } elseif ($action == 'update') {
        $barang->id = $_POST['id'];
        $barang->nama_barang = $_POST['nama_barang'];
        $barang->total = $_POST['total'];
        $barang->rusak = $_POST['rusak'] ?? 0;
        $barang->dipinjam = $_POST['dipinjam'] ?? 0;

        if ($barang->update()) {
            header("Location: ../index.php?page=barang&success=updated");
        } else {
            header("Location: ../index.php?page=barang&error=update_failed");
        }
        exit();
    }
} elseif ($action == 'delete') {
    $barang->id = $_GET['id'];
    if ($barang->delete()) {
        header("Location: ../index.php?page=barang&success=deleted");
    } else {
        header("Location: ../index.php?page=barang&error=delete_failed");
    }
    exit();
}
?>
