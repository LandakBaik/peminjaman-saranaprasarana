<?php
$database = new \App\Config\Database();
$db = $database->getConnection();

$peminjaman = new \App\Models\Peminjaman($db);

$userId = $_SESSION['user']['id'] ?? 0;

// TRUE = history
$stmt = $peminjaman->readByStaff($userId, true);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4 fw-bold">Riwayat Peminjaman</h1>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">
                    Riwayat peminjaman pada ruangan/barang yang Anda kelola
                </span>
            </div>

            <div class="card mb-4">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Peminjam</th>
                                    <th>Barang/Ruangan</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Tanggal</th>
                                    <th>Disetujui Oleh</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $no = 1;

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                    $statusClass = 'bg-secondary-subtle text-secondary';

                                    if (strtolower($row['status']) == 'ditolak') {
                                        $statusClass = 'bg-danger-subtle text-danger';
                                    }

                                    if (strtolower($row['status']) == 'dibatalkan') {
                                        $statusClass = 'bg-warning-subtle text-warning';
                                    }

                                    $nama_tampil = $row['jenis_peminjaman'] == 'ruangan'
                                        ? $row['nama_ruangan']
                                        : $row['nama_barang'];
                                    ?>

                                    <tr>

                                        <td class="text-center">
                                            <?= $no++ ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['peminjam']) ?>
                                        </td>

                                        <td>
                                            <strong>
                                                <?= htmlspecialchars($nama_tampil ?? '-') ?>
                                            </strong>
                                            <br>

                                            <small class="text-muted">
                                                <?= ucfirst($row['jenis_peminjaman']) ?>
                                            </small>
                                        </td>

                                        <td>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($row['keperluan']) ?>
                                            </small>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge <?= $statusClass ?>">
                                                <?= htmlspecialchars($row['status']) ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <?= htmlspecialchars($row['waktu_mulai']) ?>
                                        </td>

                                        <td class="text-center">
                                            <?= htmlspecialchars($row['waktu_selesai']) ?>
                                        </td>

                                        <td class="text-center">
                                            <?= date('d M Y', strtotime($row['tanggal_dibuat'])) ?>
                                        </td>

                                        <td class="text-center">
                                            <?= htmlspecialchars($row['staff_approval'] ?? '-') ?>
                                        </td>

                                    </tr>

                                <?php } ?>

                                <?php if ($stmt->rowCount() == 0): ?>

                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-3">
                                            Tidak ada riwayat peminjaman.
                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </main>
</div>