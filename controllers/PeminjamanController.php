<?php
session_start();
require_once '../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();
$peminjaman = new \App\Models\Peminjaman($db);
$barangModel = new \App\Models\Barang($db);

$action = $_GET['action'] ?? '';

// ============================================================
// AJAX: CEK STOK TERSEDIA BERDASARKAN RANGE WAKTU (GET)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'check_stock') {
    header('Content-Type: application/json');

    $id_ruangan  = $_GET['id_ruangan']  ?? null;
    $waktu_mulai = $_GET['waktu_mulai'] ?? null;
    $waktu_selesai = $_GET['waktu_selesai'] ?? null;

    if (!$id_ruangan || !$waktu_mulai || !$waktu_selesai) {
        echo json_encode(['error' => 'Parameter tidak lengkap']);
        exit();
    }

    // Normalisasi format datetime-local ("2026-05-11T07:00") ke MySQL
    $waktu_mulai   = str_replace('T', ' ', $waktu_mulai);
    $waktu_selesai = str_replace('T', ' ', $waktu_selesai);

    if ($waktu_mulai >= $waktu_selesai) {
        echo json_encode(['error' => 'Waktu mulai harus sebelum waktu selesai']);
        exit();
    }

    $availability = $barangModel->getAvailabilityByRange($id_ruangan, $waktu_mulai, $waktu_selesai);
    echo json_encode(['success' => true, 'data' => array_values($availability)]);
    exit();
}

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

            // ❗ validasi server-side stok berdasarkan range waktu
            $id_ruangan    = $_POST['ruangan'] ?? null;
            $waktu_mulai   = str_replace('T', ' ', $_POST['waktu_mulai']);
            $waktu_selesai = str_replace('T', ' ', $_POST['waktu_selesai']);

            if ($id_ruangan) {
                $availability = $barangModel->getAvailabilityByRange($id_ruangan, $waktu_mulai, $waktu_selesai);
                foreach ($items as $id_barang => $kuantitas) {
                    $stok_tersedia = isset($availability[$id_barang])
                        ? $availability[$id_barang]['stok_tersedia']
                        : 0;
                    if ($kuantitas > $stok_tersedia) {
                        $nama = $availability[$id_barang]['nama_barang'] ?? "Barang #$id_barang";
                        header("Location: ../index.php?page=pinjam&id_ruangan={$id_ruangan}&error=stok_kurang&barang=" . urlencode($nama));
                        exit();
                    }
                }
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

    // APPROVAL STAFF
    elseif ($action == 'update_status') {

        if (!isset($_SESSION['user'])) {
            die("Unauthorized");
        }

        $id_peminjaman  = $_POST['id_peminjaman'];
        $status_baru    = $_POST['status'];

        // ─── Validasi stok hanya saat MENYETUJUI ────────────────────────────
        if ($status_baru === 'Disetujui') {

            // Ambil data lengkap peminjaman + detail barang
            $dataPeminjaman = $peminjaman->getById($id_peminjaman);

            if (!$dataPeminjaman) {
                header("Location: ../index.php?page=approve-peminjaman&error=not_found");
                exit();
            }

            $waktu_mulai   = $dataPeminjaman['waktu_mulai'];
            $waktu_selesai = $dataPeminjaman['waktu_selesai'];
            $id_ruangan    = $dataPeminjaman['id_ruangan'];
            $items         = $dataPeminjaman['items']; // [id_barang => kuantitas]

            if (!empty($items) && $id_ruangan) {
                $availability = $barangModel->getAvailabilityByRange(
                    $id_ruangan,
                    $waktu_mulai,
                    $waktu_selesai
                );

                foreach ($items as $id_barang => $kuantitas) {
                    // Stok yang dihitung sudah mengecualikan peminjaman ini sendiri
                    // karena statusnya masih 'Pending' (belum 'Disetujui'/'Dipinjam')
                    $stok_tersedia = isset($availability[$id_barang])
                        ? $availability[$id_barang]['stok_tersedia']
                        : 0;

                    if ($kuantitas > $stok_tersedia) {
                        $nama = $availability[$id_barang]['nama_barang'] ?? "Barang #$id_barang";
                        header("Location: ../index.php?page=approve-peminjaman&error=stok_kurang&barang=" . urlencode($nama));
                        exit();
                    }
                }
            }
        }
        // ────────────────────────────────────────────────────────────────────

        $peminjaman->id_peminjaman = $id_peminjaman;
        $peminjaman->status        = $status_baru;
        $peminjaman->approved_by   = $_SESSION['user']['id'];
        $peminjaman->keterangan    = $_POST['keterangan'] ?? '';

        if ($peminjaman->updateStatus()) {
            // Auto reject overlapping if it's a room loan and being approved
            if ($status_baru === 'Disetujui' && isset($dataPeminjaman) && $dataPeminjaman['jenis_peminjaman'] === 'ruangan' && !empty($dataPeminjaman['id_ruangan'])) {
                $peminjaman->rejectOverlappingRuanganRequests(
                    $id_peminjaman,
                    $dataPeminjaman['id_ruangan'],
                    $dataPeminjaman['waktu_mulai'],
                    $dataPeminjaman['waktu_selesai'],
                    $_SESSION['user']['id']
                );
            }
            header("Location: ../index.php?page=approve-peminjaman&success=updated");
        } else {
            header("Location: ../index.php?page=approve-peminjaman&error=failed");
        }
        exit();
    }

    // AJUKAN PENGEMBALIAN (USER)
    elseif ($action == 'ajukan_pengembalian') {
        if (!isset($_SESSION['user'])) {
            die("Unauthorized");
        }

        $peminjaman->id_peminjaman = $_POST['id_peminjaman'];
        $peminjaman->status = 'Pengembalian';

        if ($peminjaman->updateStatusOnly()) {
            header("Location: ../index.php?page=peminjaman-saya&success=pengembalian");
        } else {
            header("Location: ../index.php?page=peminjaman-saya&error=failed");
        }
        exit();
    }

    // VERIFIKASI PENGEMBALIAN (STAFF)
    elseif ($action == 'verifikasi_pengembalian') {
        if (!isset($_SESSION['user'])) {
            die("Unauthorized");
        }

        $peminjaman->id_peminjaman = $_POST['id_peminjaman'];
        $peminjaman->status = 'Selesai';

        if ($peminjaman->updateStatusOnly()) {
            header("Location: ../index.php?page=pengembalian&success=updated");
        } else {
            header("Location: ../index.php?page=pengembalian&error=failed");
        }
        exit();
    }

    // HAPUS PEMINJAMAN (USER)
    elseif ($action == 'delete') {
        if (!isset($_SESSION['user'])) {
            die("Unauthorized");
        }

        $peminjaman->id_peminjaman = $_POST['id_peminjaman'];

        if ($peminjaman->delete()) {
            header("Location: ../index.php?page=peminjaman-saya&success=deleted");
        } else {
            header("Location: ../index.php?page=peminjaman-saya&error=failed");
        }
        exit();
    }
}
?>