<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$database = new \App\Config\Database();
$db = $database->getConnection();
$peminjaman = new \App\Models\Peminjaman($db);
$userId = $_SESSION['user']['id'] ?? 0;
$stmt = $peminjaman->readByStaff($userId);

?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <!-- Title -->
            <h1 class="mt-4">Verifikasi Pengembalian</h1>

            <!-- Action -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Kelola verifikasi pengembalian untuk ruangan Anda</span>
            </div>

            <!-- Card -->
            <div class="card mb-4">
                <div class="card-body">

                    <!-- Search + Filter -->
                    <div class="d-flex mb-3">
                        <button class="btn btn-light border me-2">
                            <i class="fas fa-filter"></i>
                        </button>
                        <input type="text" class="form-control w-25" placeholder="Search...">
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Peminjam</th>
                                    <th>Tipe</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $ada_data = false;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    if (strtolower($row['status']) != 'pengembalian' && strtolower($row['status']) != 'menunggu pengembalian') {
                                        continue;
                                    }
                                    $ada_data = true;
                                    
                                    $statusClass = 'bg-warning-subtle text-warning';

                                    // Untuk peminjaman ruangan, biasanya jenisnya adalah ruangan
                                    $nama_tampil = $row['jenis_peminjaman'] == 'ruangan' ? $row['nama_ruangan'] : $row['nama_barang'];
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['peminjam'] ?? '-') ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($nama_tampil ?? '-') ?></strong><br>
                                        <small class="text-muted"><?= ucfirst($row['jenis_peminjaman']) ?></small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($row['keperluan']) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= $statusClass ?>"><?= ucfirst(htmlspecialchars($row['status'])) ?></span>
                                    </td>
                                    <td class="text-center"><?= htmlspecialchars($row['waktu_mulai']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['waktu_selesai']) ?></td>
                                    <td class="text-center">
                                        <?= htmlspecialchars(date('d M Y', strtotime($row['tanggal_dibuat']))) ?>
                                    </td>
                                    <td class="text-center">
                                        <form action="controllers/PeminjamanController.php?action=verifikasi_pengembalian" method="POST" class="d-inline">
                                            <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman'] ?>">
                                            <button type="submit" class="btn btn-primary btn-sm mb-1" onclick="return confirm('Verifikasi pengembalian ini?')">Verifikasi Pengembalian</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if (!$ada_data): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">Tidak ada pengajuan pengembalian untuk ruangan Anda.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</div>
