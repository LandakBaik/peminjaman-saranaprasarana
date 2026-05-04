<?php
// require_once '../../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();
$ruangan = new \App\Models\Ruangan($db);
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
                                    <th>Tipe</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <tr>
                                        <td class="text-center"><input type="checkbox"></td>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><strong><?= htmlspecialchars($row['nama_ruangan']) ?></strong></td>
                                        <td class="text-center"><?= htmlspecialchars($row['kapasitas']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-info text-dark">
                                                <?= ucfirst($row['tipe_ruangan']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <img src="<?= $row['foto_ruangan'] ?>" width="60" class="rounded">
                                        </td>
                                        <td class="text-center">
                                            <button
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editRuanganModal"
                                                onclick="
                                                    document.getElementById('edit_id').value='<?= $row['id_ruangan'] ?>';
                                                    document.getElementById('edit_nama').value='<?= htmlspecialchars($row['nama_ruangan'], ENT_QUOTES) ?>';
                                                    document.getElementById('edit_kapasitas').value='<?= $row['kapasitas'] ?>';
                                                    document.getElementById('edit_tipe').value='<?= $row['tipe_ruangan'] ?>';
                                                    document.getElementById('edit_foto_lama').value='<?= $row['foto_ruangan'] ?>';
                                                    document.getElementById('edit_preview').src='<?= $row['foto_ruangan'] ?>';
                                                ">
                                                Edit
                                            </button>
                                            <a href="controllers/RuanganController.php?action=delete&id_ruangan=<?= $row['id_ruangan'] ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin menghapus ruangan ini?')">
                                                Hapus
                                            </a>
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

                        <form action="controllers/RuanganController.php?action=create" method="POST" enctype="multipart/form-data">
                            <div class="row g-4">

                                <!-- Left -->
                                <div class="col-md-7">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Nama Ruangan</label>
                                            <input type="text" class="form-control" name="nama_ruangan" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Kapasitas</label>
                                            <input type="number" class="form-control" name="kapasitas" required>
                                        </div>
                                    </div>

                                    <!-- ENUM TIPE -->
                                    <div class="mb-3">
                                        <label class="form-label">Tipe Ruangan</label>
                                        <select name="tipe_ruangan" class="form-control" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="laboratorium">Laboratorium</option>
                                            <option value="non-laboratorium">Non-Laboratorium</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Right (Upload) -->
                                <div class="col-md-5">
                                    <label class="form-label">Foto Ruangan</label>

                                    <div class="bg-light w-100 d-flex flex-column align-items-center justify-content-center border rounded p-3"
                                        style="min-height: 250px; cursor: pointer;"
                                        onclick="document.getElementById('fotoInput').click();">

                                        <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                        <span class="text-muted">Klik untuk upload</span>

                                        <input type="file" name="foto_ruangan" id="fotoInput" class="d-none" required>
                                    </div>

                                    <!-- Preview -->
                                    <img id="previewImg" class="mt-2 w-100 d-none rounded" />
                                </div>

                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3">
                                <span class="text-muted small">
                                    Lihat <a href="#" class="text-decoration-none text-primary">Ketentuan Peminjaman</a>
                                </span>

                                <div>
                                    <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Ruangan -->
        <div class="modal fade" id="editRuanganModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0 px-5 pb-4">
                        <div class="text-center mb-4">
                            <h2 class="text-warning fw-bold" id="tambahRuanganModalLabel">Edit Ruangan</h2>
                            <div class="d-flex align-items-center justify-content-center mt-3">
                                <hr class="w-25">
                                <span class="text-muted mx-3">Data Ruangan</span>
                                <hr class="w-25">
                            </div>
                        </div>

                        <form action="controllers/RuanganController.php?action=update" method="POST" enctype="multipart/form-data">

                            <!-- ID -->
                            <input type="hidden" name="id_ruangan" id="edit_id">

                            <!-- FOTO LAMA (WAJIB) -->
                            <input type="hidden" name="foto_lama" id="edit_foto_lama">

                            <div class="mb-3">
                                <label>Nama Ruangan</label>
                                <input type="text" name="nama_ruangan" id="edit_nama" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Kapasitas</label>
                                <input type="number" name="kapasitas" id="edit_kapasitas" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Tipe</label>
                                <select name="tipe_ruangan" id="edit_tipe" class="form-control">
                                    <option value="laboratorium">Laboratorium</option>
                                    <option value="non-laboratorium">Non-Laboratorium</option>
                                </select>
                            </div>

                            <!-- Preview -->
                            <div class="mb-3">
                                <img id="edit_preview" width="120">
                            </div>

                            <div class="mb-3">
                                <label>Ganti Foto (opsional)</label>
                                <input type="file" name="foto_ruangan" class="form-control">
                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3">
                                <span class="text-muted small">
                                    Lihat <a href="#" class="text-decoration-none text-primary">Ketentuan Peminjaman</a>
                                </span>

                                <div>
                                    <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-warning">Kirim</button>
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