<?php
$database = new \App\Config\Database();
$db = $database->getConnection();

$peminjaman = new \App\Models\Peminjaman($db);

$userId = $_SESSION['user']['id'] ?? 0;

// TRUE = history
$stmt = $peminjaman->readByUser($userId, true);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <!-- Title -->
            <h1 class="mt-4 fw-bold">Riwayat Peminjaman</h1>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted"> Daftar peminjaman yang telah selesai, ditolak, atau dibatalkan</span>
                <a href="index.php?page=peminjaman-saya" class="btn btn-primary btn-sm">Kembali</a>
            </div>
            <!-- Card -->
            <div class="card mb-4">
                <div class="card-body">

                    <!-- Search -->
                    <div class="d-flex align-items-center mb-3">
                        <input
                            type="text"
                            class="form-control"
                            style="max-width: 250px; height: 42px;"
                            placeholder="Search..."
                            id="searchInput">
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">

                        <table class="table table-bordered align-middle" id="peminjamanTable">

                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Nama Peminjaman</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>

                            <tbody id="peminjamanBody">

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

                                    $nama_tampil = $row['nama_barang']
                                        ?? $row['nama_ruangan']
                                        ?? '-';
                                    ?>

                                        <tr>

                                            <td class="text-center">
                                                <?= $no++ ?>
                                            </td>

                                            <td>
                                                <strong>
                                                    <?= htmlspecialchars($nama_tampil) ?>
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

                                                <?php if (!empty($row['staff_approval'])): ?>

                                                        <br>

                                                        <small class="text-primary">
                                                            Disetujui oleh:
                                                            <?= htmlspecialchars($row['staff_approval']) ?>
                                                        </small>

                                                <?php endif; ?>
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

                                        </tr>

                                <?php } ?>

                                <?php if ($stmt->rowCount() == 0): ?>

                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">
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

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</div>