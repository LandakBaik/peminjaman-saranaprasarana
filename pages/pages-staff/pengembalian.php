<?php
// View variables are provided by PageController
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
                    <div class="d-flex align-items-center mb-3">
                        <!-- Tombol Filter -->
                        <button type="button"
                            class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center me-3"
                            style="width: 42px; height: 42px;" data-bs-toggle="modal" data-bs-target="#filterModal"
                            id="btnFilterToggle" title="Filter">
                            <i class="fas fa-filter" style="font-size: 16px;"></i>
                        </button>

                        <!-- Search Bar -->
                        <input type="text" class="form-control" style="max-width: 250px; height: 42px;"
                            placeholder="Search..." id="searchInput">
                    </div>

                    <!-- popup filter -->
                    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-semibold" id="filterModalLabel">Filter Data</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body pt-2">
                                    <!-- Jenis -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jenis</label>
                                        <select class="form-select" id="filterJenis">
                                            <option value="" selected>Semua</option>
                                            <option value="Barang">Barang</option>
                                            <option value="Ruangan">Ruangan</option>
                                        </select>
                                    </div>
                                    <!-- Status Kembali -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Status Kembali</label>
                                        <select class="form-select" id="filterStatusKembali">
                                            <option value="" selected>Semua</option>
                                            <option value="Terlambat">Terlambat</option>
                                            <option value="Tepat Waktu">Tepat Waktu</option>
                                        </select>
                                    </div>
                                    <!-- Tanggal -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tanggal Pengajuan</label>
                                        <input type="date" class="form-control" id="filterTanggal">
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary"
                                        id="btnResetFilter">Reset</button>
                                    <button type="button" class="btn btn-primary" id="btnApplyFilter"
                                        data-bs-dismiss="modal">Terapkan</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Peminjam</th>
                                    <th>Tipe</th>
                                    <th>Ruangan</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status Kembali</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="peminjamanBody">
                                <?php
                                $no = 1;
                                $ada_data = false;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    if (strtolower($row['status']) != 'pengembalian' && strtolower($row['status']) != 'menunggu pengembalian') {
                                        continue;
                                    }
                                    $ada_data = true;

                                    // Hitung status kembali
                                    $waktu_selesai = strtotime($row['waktu_selesai']);
                                    $waktu_pengajuan = strtotime($row['tanggal_diubah']);
                                    $status_kembali = ($waktu_pengajuan > $waktu_selesai) ? 'Terlambat' : 'Tepat Waktu';
                                ?>
                                <tr data-status="<?= ucfirst(htmlspecialchars($row['status'])) ?>"
                                    data-type="<?= ucfirst(htmlspecialchars($row['jenis_peminjaman'])) ?>"
                                    data-date="<?= htmlspecialchars(date('Y-m-d', strtotime($row['tanggal_diubah'] ?? 'now'))) ?>"
                                    data-room="<?= htmlspecialchars($row['nama_ruangan'] ?? '-') ?>"
                                    data-peminjam="<?= htmlspecialchars($row['peminjam'] ?? '-') ?>"
                                    data-status-kembali="<?= $status_kembali ?>">
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['peminjam'] ?? '-') ?></strong><br>
                                        <small class="text-muted">ID: #<?= $row['id_pengguna'] ?></small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge border text-dark bg-light">
                                            <?= ucfirst($row['jenis_peminjaman']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <strong><?= htmlspecialchars($row['nama_ruangan'] ?? '-') ?></strong>
                                    </td>
                                    <td class="text-center small">
                                        <?= date('d M Y - H:i', strtotime($row['waktu_mulai'])) ?>
                                    </td>
                                    <td class="text-center small">
                                        <?= date('d M Y - H:i', strtotime($row['waktu_selesai'])) ?>
                                    </td>
                                    <td class="text-center small">
                                        <?= htmlspecialchars(date('d M Y - H:i', strtotime($row['tanggal_diubah']))) ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if ($status_kembali == 'Terlambat') {
                                            echo '<span class="badge bg-danger">Terlambat</span>';
                                        } else {
                                            echo '<span class="badge bg-success">Tepat Waktu</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <form action="controllers/PeminjamanController.php?action=verifikasi_pengembalian" method="POST" class="d-inline">
                                            <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman'] ?>">
                                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Verifikasi pengembalian ini?')">Verifikasi Pengembalian</button>
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

                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted" id="rowCount">Menampilkan 0 data</small>

                        <div class="d-flex align-items-center">
                            <small class="me-2">Rows per page: 
                                <select id="rowsPerPage" class="form-select form-select-sm d-inline-block w-auto border-0 bg-transparent py-0" style="cursor: pointer; box-shadow: none;">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                </select>
                            </small>
                            <button class="btn btn-light btn-sm me-1" id="btnPrevPage">&lt;</button>
                            <span id="currentPageNum" class="mx-2">1</span>
                            <button class="btn btn-light btn-sm ms-1" id="btnNextPage">&gt;</button>
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
