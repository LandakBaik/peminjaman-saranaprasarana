<?php
// require_once '../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();
$peminjaman = new \App\Models\Peminjaman($db);
$userId = $_SESSION['user']['id'] ?? 0;
$stmt = $peminjaman->readByUser($userId);
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <!-- Title -->
            <h1 class="mt-4 fw-bold">Peminjaman Saya</h1>

            <!-- Action -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Peminjaman anda saat ini</span>
                <a href="index.php?page=riwayat-peminjaman" class="btn btn-primary btn-sm">
                    <i class="fas fa-history me-1"></i> Lihat Riwayat
                </a>
            </div>

            <!-- Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Search + Filter -->
                    <div class="d-flex align-items-center mb-3">
                        <!-- Tombol Filter -->
                        <button type="button" class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px;" data-bs-toggle="modal" data-bs-target="#filterModal" id="btnFilterToggle" title="Filter">
                            <i class="fas fa-filter" style="font-size: 16px;"></i>
                        </button>

                        <!-- Search Bar -->
                        <input type="text" class="form-control" style="max-width: 250px; height: 42px;" placeholder="Search..." id="searchInput">
                    </div>

                    <!-- popup filter -->
                    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-semibold" id="filterModalLabel">Filter Data</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body pt-2">
                                    <!-- Status -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select class="form-select" id="filterStatus">
                                            <option value="" selected>Semua</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Disetujui">Disetujui</option>
                                            <option value="Ditolak">Ditolak</option>
                                        </select>
                                    </div>
                                    <!-- Jenis -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jenis</label>
                                        <select class="form-select" id="filterJenis">
                                            <option value="" selected>Semua</option>
                                            <option value="Barang">Barang</option>
                                            <option value="Ruangan">Ruangan</option>
                                        </select>
                                    </div>
                                    <!-- Tanggal -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tanggal</label>
                                        <input type="date" class="form-control" id="filterTanggal">
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary" id="btnResetFilter">Reset</button>
                                    <button type="button" class="btn btn-primary" id="btnApplyFilter" data-bs-dismiss="modal">Terapkan</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="peminjamanTable">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>No</th>
                                    <th>Nama Peminjaman</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="peminjamanBody">
                                <?php
                                $no = 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $statusClass = 'bg-primary-subtle text-primary';
                                    if ($row['status'] == 'approved') $statusClass = 'bg-success-subtle text-success';
                                    if ($row['status'] == 'rejected') $statusClass = 'bg-danger-subtle text-danger';
                                    if ($row['status'] == 'returned') $statusClass = 'bg-secondary-subtle text-secondary';
                                ?>
                                    <tr data-status="<?= ucfirst(htmlspecialchars($row['status'])) ?>" data-type="<?= ucfirst(htmlspecialchars($row['jenis_peminjaman'])) ?>">
                                        <td class="text-center"><input type="checkbox" class="row-checkbox"></td>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($row['nama_barang'] ?? '-') ?></strong><br>
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
                                        <td class="text-center">
                                            <?= htmlspecialchars($row['waktu_mulai']) ?>
                                        </td>

                                        <td class="text-center">
                                            <?= htmlspecialchars($row['waktu_selesai']) ?>
                                        </td>
                                        <td class="text-center">
                                            <?= htmlspecialchars($row['tanggal_dibuat'] ?? '-') ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['status'] == 'pending'): ?>
                                                <button class="btn btn-danger btn-sm">Batalkan</button>
                                            <?php elseif ($row['status'] == 'approved'): ?>
                                                <button class="btn btn-info btn-sm">Kembalikan</button>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted" id="rowCount">1–2 of 2</small>

                        <div class="d-flex align-items-center">
                            <small class="me-2">Rows per page: 10</small>
                            <button class="btn btn-light btn-sm me-1">&lt;</button>
                            <span>1</span>
                            <button class="btn btn-light btn-sm ms-1">&gt;</button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</div>
