<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Peminjaman.php';
require_once '../models/Barang.php';
require_once '../models/Ruangan.php';

$database = new Database();
$db = $database->getConnection();
$peminjaman = new Peminjaman($db);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action == 'create') {
        $peminjaman->user_id = $_SESSION['user']['id'];
        $peminjaman->jenis_peminjaman = $_POST['jenis_peminjaman'];
        $peminjaman->item_id = $_POST['item_id'];
        $peminjaman->jumlah = $_POST['jumlah'] ?? 1;
        $peminjaman->tanggal_pinjam = $_POST['tanggal_pinjam'];
        $peminjaman->tanggal_kembali = $_POST['tanggal_kembali'];
        $peminjaman->keperluan = $_POST['keperluan'];

        if ($peminjaman->create()) {
            header("Location: ../index.php?page=peminjaman-saya&success=requested");
        } else {
            header("Location: ../index.php?page=pinjam&error=request_failed");
        }
        exit();
    } elseif ($action == 'update_status') {
        // For staff/admin approving or rejecting
        $peminjaman->id = $_POST['id'];
        $peminjaman->status = $_POST['status'];
        $peminjaman->approved_by = $_SESSION['user']['id'];

        // If approved and it's barang, we should update the 'dipinjam' count (simplified logic)
        // In a real app, we'd wrap this in a transaction.

        if ($peminjaman->updateStatus()) {
            header("Location: ../index.php?page=approve-peminjaman&success=status_updated");
        } else {
            header("Location: ../index.php?page=approve-peminjaman&error=update_failed");
        }
        exit();
    }
}
?>
