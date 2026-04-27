<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Peminjaman.php';

$database = new Database();
$db = $database->getConnection();
$peminjaman = new Peminjaman($db);

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    if ($action == 'create') {

       
        if (!isset($_SESSION['user'])) {
            die("Harus login!");
        }

     
        $peminjaman->id_pengguna = $_SESSION['user']['id'];
        $peminjaman->jenis_peminjaman = $_POST['jenis_peminjaman'];
        $peminjaman->waktu_mulai = $_POST['waktu_mulai'];
        $peminjaman->waktu_selesai = $_POST['waktu_selesai'];
        // $peminjaman->tanggal_kembali = $_POST['tanggal_kembali']??null;
        $peminjaman->keperluan = $_POST['keperluan'] ?? '';
        $peminjaman->catatan = $_POST['catatan'] ?? '';

        $peminjaman->jaminan = null;

        if (!empty($_FILES['jaminan']['name'])) {
            $ext = pathinfo($_FILES['jaminan']['name'], PATHINFO_EXTENSION);
            $fileName = 'jaminan_' . time() . '.' . $ext;

            move_uploaded_file(
                $_FILES['jaminan']['tmp_name'],
                "../uploads/" . $fileName
            );

            $peminjaman->jaminan = $fileName;
        }

        if ($_POST['jenis_peminjaman'] == 'barang') {

            $items = [];

            if (!empty($_POST['barang'])) {
                foreach ($_POST['barang'] as $id => $data) {

                    // hanya ambil yang dicentang
                    if (isset($data['checked'])) {

                        $jumlah = $data['jumlah'] ?? 1;

                        if ($jumlah < 1) $jumlah = 1;

                        $items[$id] = $jumlah;
                    }
                }
            }

            // ❗ validasi minimal pilih barang
            if (empty($items)) {
                die("Pilih minimal 1 barang!");
            }

            $peminjaman->items = $items;

        } else {
            if (empty($_POST['ruangan'])) {
                die("Ruangan tidak valid!");
            }

            $peminjaman->id_ruangan = $_POST['ruangan'];
        }

        if ($peminjaman->create()) {
            header("Location: ../index.php?page=peminjaman-saya&success=created");
        } else {
            header("Location: ../index.php?page=pinjam&error=failed");
        }
        exit();
    }

    // 🔥 APPROVAL STAFF
    elseif ($action == 'update_status') {

        if (!isset($_SESSION['user'])) {
            die("Unauthorized");
        }

        $peminjaman->id_peminjaman = $_POST['id_peminjaman'];
        $peminjaman->status = $_POST['status'];
        $peminjaman->approved_by = $_SESSION['user']['id'];

        if ($peminjaman->updateStatus()) {
            header("Location: ../index.php?page=approve-peminjaman&success=updated");
        } else {
            header("Location: ../index.php?page=approve-peminjaman&error=failed");
        }
        exit();
    }
}
?>