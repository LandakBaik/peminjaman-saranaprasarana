<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;

class PageController
{
    private $db;

    public function __construct()
    {
        // Koneksi database
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function detailProfil()
    {
        $userId = $_SESSION['user']['id'] ?? 0;

        // Ambil data profil
        $query = "
            SELECT * FROM detail_profil 
            WHERE id_pengguna = :id 
            LIMIT 1
        ";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(":id", $userId);

        $stmt->execute();

        $profil = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$profil) {
            $profil = [];
        }

        include 'pages/detail-profil.php';
    }

    public function detailProfilEdit()
    {
        $userId = $_SESSION['user']['id'] ?? 0;

        // Ambil data profil
        $query = "
            SELECT * FROM detail_profil 
            WHERE id_pengguna = :id 
            LIMIT 1
        ";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(":id", $userId);

        $stmt->execute();

        $profil = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$profil) {
            $profil = [];
        }

        include 'pages/detail-profil-edit.php';
    }

    public function ketentuan()
    {
        include 'pages/ketentuan.php';
    }

    public function selectRoom()
    {
        $ruanganModel = new \App\Models\Ruangan($this->db);

        $stmt = $ruanganModel->readAll();

        $labs = [];
        $nonLabs = [];

        // Pisahkan laboratorium dan non laboratorium
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {

            if (
                strtolower($r['tipe_ruangan'] ?? '') === 'laboratorium'
            ) {
                $labs[] = $r;

            } else {
                $nonLabs[] = $r;
            }
        }

        include 'pages/pages-peminjam/select-room.php';
    }

    public function pinjam()
    {
        $id_ruangan = $_GET['id_ruangan'] ?? null;

        // Validasi ruangan
        if (!$id_ruangan) {
            die("Ruangan tidak valid (missing id_ruangan)");
        }

        $ruanganModel = new \App\Models\Ruangan($this->db);

        $ruangan = $ruanganModel->getById($id_ruangan);

        if (!$ruangan) {
            die("Ruangan tidak ditemukan");
        }

        $roomName = $ruangan['nama_ruangan'];

        // Data barang ruangan
        $barangModel = new \App\Models\Barang($this->db);

        $barangList = $barangModel->getByRuangan($id_ruangan);

        // Tanggal booking
        $peminjamanModel = new \App\Models\Peminjaman($this->db);

        $bookedDates = $peminjamanModel
            ->getApprovedDatesByRoom($id_ruangan);

        include 'pages/pages-peminjam/pinjam.php';
    }

    public function peminjamanSaya()
    {
        $peminjaman = new \App\Models\Peminjaman($this->db);

        $userId = $_SESSION['user']['id'] ?? 0;

        // Update status terlambat
        $peminjaman->updateLateStatus($userId);

        $stmt = $peminjaman->readByUser($userId);

        include 'pages/pages-peminjam/peminjaman-saya.php';
    }

    public function riwayatPeminjaman()
    {
        $peminjaman = new \App\Models\Peminjaman($this->db);

        $userId = $_SESSION['user']['id'] ?? 0;

        // Ambil riwayat peminjaman
        $stmt = $peminjaman->readByUser($userId, true);

        include 'pages/pages-peminjam/riwayat-peminjaman.php';
    }

    public function approvePeminjaman()
    {
        $peminjaman = new \App\Models\Peminjaman($this->db);

        $userId = $_SESSION['user']['id'] ?? 0;

        $stmt = $peminjaman->readByStaff($userId);

        include 'pages/pages-staff/approve.php';
    }

    public function pengembalian()
    {
        $peminjaman = new \App\Models\Peminjaman($this->db);

        $userId = $_SESSION['user']['id'] ?? 0;

        $stmt = $peminjaman->readByStaff($userId);

        include 'pages/pages-staff/pengembalian.php';
    }

    public function riwayatPeminjamanStaff()
    {
        $peminjaman = new \App\Models\Peminjaman($this->db);

        $userId = $_SESSION['user']['id'] ?? 0;

        $stmt = $peminjaman->readByStaff($userId, true);

        include 'pages/pages-staff/riwayat-peminjaman.php';
    }

    public function ruanganBarang()
    {
        $ruangan = new \App\Models\Ruangan($this->db);

        $stmt = $ruangan->readAll();

        include 'pages/pages-admin/ruangan-barang.php';
    }

    public function daftarAkun()
    {
        $userModel = new \App\Models\User($this->db);

        $stmt = $userModel->readByRoles([
            'user',
            'admin',
            'staff'
        ]);

        include 'pages/pages-admin/daftar-akun.php';
    }

    public function akunStaff()
    {
        $userModel = new \App\Models\User($this->db);

        $stmt = $userModel->readByRoles(['staff']);

        include 'pages/pages-admin/akun-staff.php';
    }

    public function riwayatPeminjamanAdmin()
    {
        $peminjaman = new \App\Models\Peminjaman($this->db);

        $stmt = $peminjaman->readAll(true);

        include 'pages/pages-admin/riwayat-peminjaman.php';
    }
}