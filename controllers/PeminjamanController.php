<?php
session_start();

require_once '../config/Autoloader.php';

// Koneksi database
$database = new \App\Config\Database();
$db = $database->getConnection();

$peminjaman = new \App\Models\Peminjaman($db);
$barangModel = new \App\Models\Barang($db);

// Ambil action
$action = $_GET['action'] ?? '';

// Cek list peminjaman pada tanggal tertentu (AJAX)
if (
    $_SERVER['REQUEST_METHOD'] === 'GET' &&
    $action === 'get_loans_by_date'
) {
    header('Content-Type: application/json');
    $id_ruangan = $_GET['id_ruangan'] ?? null;
    $date = $_GET['date'] ?? null;

    if (!$id_ruangan || !$date) {
        echo json_encode(['error' => 'Parameter tidak lengkap']);
        exit();
    }

    $loans = $peminjaman->getLoansByDateAndRoom($date, $id_ruangan);
    echo json_encode(['success' => true, 'data' => $loans]);
    exit();
}

// Cek stok barang (AJAX)
if (
    $_SERVER['REQUEST_METHOD'] === 'GET' &&
    $action === 'check_stock'
) {

    header('Content-Type: application/json');

    $id_ruangan = $_GET['id_ruangan'] ?? null;

    $waktu_mulai = $_GET['waktu_mulai'] ?? null;

    $waktu_selesai = $_GET['waktu_selesai'] ?? null;

    // Validasi parameter
    if (
        !$id_ruangan ||
        !$waktu_mulai ||
        !$waktu_selesai
    ) {

        echo json_encode([
            'error' => 'Parameter tidak lengkap'
        ]);

        exit();
    }

    // Format datetime
    $waktu_mulai =
        str_replace('T', ' ', $waktu_mulai);

    $waktu_selesai =
        str_replace('T', ' ', $waktu_selesai);

    // Validasi waktu
    if ($waktu_mulai >= $waktu_selesai) {

        echo json_encode([
            'error' => 'Waktu mulai harus sebelum waktu selesai'
        ]);

        exit();
    }

    // Ambil stok tersedia
    $availability = $barangModel->getAvailabilityByRange(
        $id_ruangan,
        $waktu_mulai,
        $waktu_selesai
    );

    echo json_encode([
        'success' => true,
        'data' => array_values($availability)
    ]);

    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Create peminjaman
    if ($action == 'create') {

        // Cegah double request
        if (
            isset($_SESSION['last_peminjaman_submit']) &&
            (time() - $_SESSION['last_peminjaman_submit']) < 15
        ) {

            $roomId = $_POST['ruangan'] ?? '';

            if (!empty($roomId)) {

                header("Location: ../index.php?page=pinjam&id_ruangan={$roomId}&error=duplicate_request");

            } else {

                header("Location: ../index.php?page=peminjaman-saya&error=duplicate_request");
            }

            exit();
        }

        $_SESSION['last_peminjaman_submit'] = time();

        // Validasi login
        if (!isset($_SESSION['user'])) {
            die("Harus login!");
        }

        // Data peminjaman
        $peminjaman->id_pengguna =
            $_SESSION['user']['id'];

        $peminjaman->jenis_peminjaman =
            $_POST['jenis_peminjaman'];

        $peminjaman->waktu_mulai =
            $_POST['waktu_mulai'];

        $peminjaman->waktu_selesai =
            $_POST['waktu_selesai'];

        $peminjaman->keperluan =
            $_POST['keperluan'] ?? '';

        $peminjaman->catatan =
            $_POST['catatan'] ?? '';

        $peminjaman->jaminan = null;

        // Upload jaminan
        if (!empty($_FILES['jaminan']['name'])) {

            $ext = pathinfo(
                $_FILES['jaminan']['name'],
                PATHINFO_EXTENSION
            );

            $fileName =
                'jaminan_' . time() . '.' . $ext;

            move_uploaded_file(
                $_FILES['jaminan']['tmp_name'],
                "../uploads/" . $fileName
            );

            $peminjaman->jaminan = $fileName;
        }

        // Peminjaman barang
        if ($_POST['jenis_peminjaman'] == 'barang') {

            $items = [];

            if (!empty($_POST['barang'])) {

                foreach ($_POST['barang'] as $id => $data) {

                    // Ambil barang yang dicentang
                    if (isset($data['checked'])) {

                        $jumlah =
                            $data['kuantitas'] ??
                            $data['jumlah'] ??
                            1;

                        if ($jumlah < 1) {
                            $jumlah = 1;
                        }

                        $items[$id] = $jumlah;
                    }
                }
            }

            // Validasi barang kosong
            if (empty($items)) {
                die("Pilih minimal 1 barang!");
            }

            // Validasi stok
            $id_ruangan =
                $_POST['ruangan'] ?? null;

            $waktu_mulai =
                str_replace(
                    'T',
                    ' ',
                    $_POST['waktu_mulai']
                );

            $waktu_selesai =
                str_replace(
                    'T',
                    ' ',
                    $_POST['waktu_selesai']
                );

            if ($id_ruangan) {

                $availability =
                    $barangModel->getAvailabilityByRange(
                        $id_ruangan,
                        $waktu_mulai,
                        $waktu_selesai
                    );

                foreach ($items as $id_barang => $kuantitas) {

                    $stok_tersedia =
                        isset($availability[$id_barang])
                        ? $availability[$id_barang]['stok_tersedia']
                        : 0;

                    if ($kuantitas > $stok_tersedia) {

                        $nama =
                            $availability[$id_barang]['nama_barang']
                            ?? "Barang #$id_barang";

                        header(
                            "Location: ../index.php?page=pinjam&id_ruangan={$id_ruangan}&error=stok_kurang&barang="
                            . urlencode($nama)
                        );

                        exit();
                    }
                }
            }

            $peminjaman->items = $items;

        // Peminjaman ruangan
        } else {

            if (empty($_POST['ruangan'])) {
                die("Ruangan tidak valid!");
            }

            $peminjaman->id_ruangan =
                $_POST['ruangan'];
        }

        // Simpan peminjaman
        if ($peminjaman->create()) {

            header("Location: ../index.php?page=peminjaman-saya&success=created");

        } else {

            header("Location: ../index.php?page=pinjam&error=failed");
        }

        exit();
    }

    // Approval staff
    elseif ($action == 'update_status') {

        if (!isset($_SESSION['user'])) {
            die("Unauthorized");
        }

        $id_peminjaman =
            $_POST['id_peminjaman'];

        $status_baru =
            $_POST['status'];

        // Validasi stok saat approve
        if ($status_baru === 'Disetujui') {

            $dataPeminjaman =
                $peminjaman->getById($id_peminjaman);

            if (!$dataPeminjaman) {

                header("Location: ../index.php?page=approve-peminjaman&error=not_found");

                exit();
            }

            $waktu_mulai =
                $dataPeminjaman['waktu_mulai'];

            $waktu_selesai =
                $dataPeminjaman['waktu_selesai'];

            $id_ruangan =
                $dataPeminjaman['id_ruangan'];

            $items =
                $dataPeminjaman['items'];

            if (!empty($items) && $id_ruangan) {

                $availability =
                    $barangModel->getAvailabilityByRange(
                        $id_ruangan,
                        $waktu_mulai,
                        $waktu_selesai
                    );

                foreach ($items as $id_barang => $kuantitas) {

                    $stok_tersedia =
                        isset($availability[$id_barang])
                        ? $availability[$id_barang]['stok_tersedia']
                        : 0;

                    if ($kuantitas > $stok_tersedia) {

                        // Penyesuaian stok ruangan
                        if (
                            $dataPeminjaman['jenis_peminjaman']
                            === 'ruangan'
                        ) {

                            $peminjaman->updateDetailQuantity(
                                $id_peminjaman,
                                $id_barang,
                                $stok_tersedia
                            );

                            continue;
                        }

                        $nama =
                            $availability[$id_barang]['nama_barang']
                            ?? "Barang #$id_barang";

                        header(
                            "Location: ../index.php?page=approve-peminjaman&error=stok_kurang&barang="
                            . urlencode($nama)
                        );

                        exit();
                    }
                }
            }
        }

        // Update status
        $peminjaman->id_peminjaman =
            $id_peminjaman;

        $peminjaman->status =
            $status_baru;

        $peminjaman->approved_by =
            $_SESSION['user']['id'];

        $peminjaman->keterangan =
            $_POST['keterangan'] ?? '';

        if ($peminjaman->updateStatus()) {

            // Tolak peminjaman bentrok
            if ($status_baru === 'Disetujui') {

                $peminjaman->rejectConflictingPendingLoans(
                    $id_peminjaman,
                    $_SESSION['user']['id']
                );
            }

            header("Location: ../index.php?page=approve-peminjaman&success=updated");

        } else {

            header("Location: ../index.php?page=approve-peminjaman&error=failed");
        }

        exit();
    }

    // Ajukan pengembalian
    elseif ($action == 'ajukan_pengembalian') {

        if (!isset($_SESSION['user'])) {
            die("Unauthorized");
        }

        $peminjaman->id_peminjaman =
            $_POST['id_peminjaman'];

        $peminjaman->status = 'Pengembalian';

        if ($peminjaman->updateStatusOnly()) {

            header("Location: ../index.php?page=peminjaman-saya&success=pengembalian");

        } else {

            header("Location: ../index.php?page=peminjaman-saya&error=failed");
        }

        exit();
    }

    // Verifikasi pengembalian
    elseif ($action == 'verifikasi_pengembalian') {

        if (!isset($_SESSION['user'])) {
            die("Unauthorized");
        }

        $peminjaman->id_peminjaman =
            $_POST['id_peminjaman'];

        $peminjaman->status = 'Selesai';

        if ($peminjaman->updateStatusOnly()) {

            header("Location: ../index.php?page=pengembalian&success=updated");

        } else {

            header("Location: ../index.php?page=pengembalian&error=failed");
        }

        exit();
    }

    // Hapus peminjaman
    elseif ($action == 'delete') {

        if (!isset($_SESSION['user'])) {
            die("Unauthorized");
        }

        $peminjaman->id_peminjaman =
            $_POST['id_peminjaman'];

        if ($peminjaman->delete()) {

            header("Location: ../index.php?page=peminjaman-saya&success=deleted");

        } else {

            header("Location: ../index.php?page=peminjaman-saya&error=failed");
        }

        exit();
    }

    // Export riwayat
    elseif ($action == 'export') {

        if (
            !empty($_POST['id_peminjaman']) &&
            is_array($_POST['id_peminjaman'])
        ) {

            $ids = $_POST['id_peminjaman'];

            $stmt = $peminjaman->getByIds($ids);

            // Header export excel
            header("Content-Type: application/vnd.ms-excel");

            header("Content-Disposition: attachment; filename=Riwayat_Peminjaman.xls");

            header("Pragma: no-cache");

            header("Expires: 0");

            echo "<table border='1'>";

            echo "<tr>";
            echo "<th>Kode Peminjaman</th>";
            echo "<th>Peminjam</th>";
            echo "<th>Jenis Peminjaman</th>";
            echo "<th>Nama Ruangan / Barang</th>";
            echo "<th>Waktu Mulai</th>";
            echo "<th>Waktu Selesai</th>";
            echo "<th>Tanggal Pengajuan</th>";
            echo "<th>Status</th>";
            echo "<th>Disetujui Oleh</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

                $tgl = date(
                    'dmY',
                    strtotime($row['tanggal_dibuat'])
                );

                $kode =
                    "PJM-" . $tgl . "-" . $row['id_peminjaman'];

                $item_detail =
                    ($row['jenis_peminjaman'] == 'ruangan')
                    ? ($row['nama_ruangan'] ?? '-')
                    : ($row['nama_barang'] ?? '-');

                echo "<tr>";

                echo "<td>" . htmlspecialchars($kode) . "</td>";

                echo "<td>" . htmlspecialchars($row['peminjam'] ?? '-') . "</td>";

                echo "<td>" . htmlspecialchars(
                    ucfirst($row['jenis_peminjaman'] ?? '-')
                ) . "</td>";

                echo "<td>" . htmlspecialchars($item_detail) . "</td>";

                echo "<td>" . htmlspecialchars(
                    date('d M Y - H:i', strtotime($row['waktu_mulai']))
                ) . "</td>";

                echo "<td>" . htmlspecialchars(
                    date('d M Y - H:i', strtotime($row['waktu_selesai']))
                ) . "</td>";

                echo "<td>" . htmlspecialchars(
                    date('d M Y - H:i', strtotime($row['tanggal_dibuat']))
                ) . "</td>";

                echo "<td>" . htmlspecialchars(
                    ucfirst($row['status'] ?? '-')
                ) . "</td>";

                echo "<td>" . htmlspecialchars(
                    $row['staff_approval'] ?? '-'
                ) . "</td>";

                echo "</tr>";
            }

            echo "</table>";

            exit();

        } else {

            header("Location: ../index.php?page=riwayat-peminjaman&error=no_items_selected");

            exit();
        }
    }
}
?>