<?php
require_once 'config/Database.php';
require_once 'models/Ruangan.php';

$database = new Database();
$db = $database->getConnection();
$ruangan = new Ruangan($db);
$stmt = $ruangan->readAll();
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <!-- Title -->
            <h1 class="mt-4">Ruangan</h1>

            <!-- Action -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Daftar ruangan yang tersedia</span>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahRuanganModal">
                    <i class="fas fa-plus me-1"></i> Tambah Ruangan
                </button>
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
                                    <th><input type="checkbox"></th>
                                    <th>No</th>
                                    <th>Nama Ruangan</th>
                                    <th>Kapasitas</th>
                                    <th>Status</th>
                                    <th>Fasilitas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $badgeClass = 'bg-success-subtle text-success';
                                    if ($row['status'] == 'dipinjam') $badgeClass = 'bg-warning-subtle text-warning';
                                    if ($row['status'] == 'maintenance') $badgeClass = 'bg-danger-subtle text-danger';
                                ?>
                                <tr>
                                    <td class="text-center"><input type="checkbox"></td>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($row['nama_ruangan']) ?></strong></td>
                                    <td class="text-center"><?= htmlspecialchars($row['kapasitas']) ?></td>
                                    <td class="text-center">
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst(htmlspecialchars($row['status'])) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($row['fasilitas']) ?></td>
                                    <td class="text-center">
                                        <a href="controllers/RuanganController.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin menghapus ruangan ini?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">1–2 of 2</small>

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
        
        <!-- Modal Tambah Ruangan -->
        <div class="modal fade" id="tambahRuanganModal" tabindex="-1" aria-labelledby="tambahRuanganModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0 px-5 pb-4">
                        <div class="text-center mb-4">
                            <h2 class="text-primary fw-bold" id="tambahRuanganModalLabel">Tambah Ruangan Baru</h2>
                            <div class="d-flex align-items-center justify-content-center mt-3">
                                <hr class="w-25">
                                <span class="text-muted mx-3">Data Ruangan</span>
                                <hr class="w-25">
                            </div>
                        </div>

                        <form action="controllers/RuanganController.php?action=create" method="POST">
                            <div class="row g-4">
                                <!-- Left Column -->
                                <div class="col-md-7">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted fw-semibold">Nama Ruangan</label>
                                            <input type="text" class="form-control" name="nama_ruangan" placeholder="Contoh: Ruang 3.11" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted fw-semibold">Kapasitas</label>
                                            <input type="number" class="form-control" name="kapasitas" placeholder="50" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted fw-semibold">Fasilitas</label>
                                        <textarea class="form-control" name="fasilitas" rows="4" placeholder="AC, Proyektor, dll..."></textarea>
                                    </div>
                                </div>

                                <!-- Right Column (Image upload placeholder) -->
                                <div class="col-md-5">
                                    <div class="bg-light w-100 h-100 d-flex flex-column align-items-center justify-content-center border rounded" style="min-height: 250px; cursor: pointer;">
                                        <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                        <span class="text-muted">Upload Gambar Ruangan</span>
                                        <input type="file" class="d-none" id="uploadGambarRuangan">
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3">
                                <span class="text-muted small">
                                    Lihat <a href="#" class="text-decoration-none text-primary">Ketentuan Peminjaman <i class="fas fa-search ms-1"></i></a>
                                </span>
                                <div>
                                    <button type="button" class="btn btn-outline-secondary px-4 me-2" data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-primary px-4">Kirim</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</div>