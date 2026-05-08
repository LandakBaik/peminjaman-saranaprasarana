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
                                    <th>Tanggal Dibuat</th>
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
                                            <?= date('d M Y', strtotime($row['tanggal_dibuat'])) ?>
                                        </td>
                                        <td class="text-center">
                                            <button
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editRuanganModal"
                                                data-id="<?= $row['id_ruangan'] ?>"
                                                data-nama="<?= htmlspecialchars($row['nama_ruangan'], ENT_QUOTES) ?>"
                                                data-kapasitas="<?= $row['kapasitas'] ?>"
                                                data-tipe="<?= $row['tipe_ruangan'] ?>"
                                                data-foto="<?= htmlspecialchars($row['foto_ruangan'] ?? '', ENT_QUOTES) ?>">
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
                                    <label class="form-label">Upload Foto Ruangan</label>

                                    <div id="uploadBox"
                                        class="bg-light w-100 d-flex flex-column align-items-center justify-content-center border rounded p-3 overflow-hidden"
                                        style="min-height: 250px; cursor: pointer;"
                                        onclick="document.getElementById('fotoInput').click();">

                                        <!-- Placeholder -->
                                        <div id="uploadPlaceholder"
                                            class="d-flex flex-column align-items-center object-fit: contain; justify-content-center">

                                            <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                            <span class="text-muted">Klik untuk upload</span>
                                        </div>

                                        <!-- Input -->
                                        <input type="file"
                                            name="foto_ruangan"
                                            id="fotoInput"
                                            class="d-none"
                                            accept="image/*">

                                        <!-- Preview -->
                                        <img id="previewImg"
                                            class="w-100 rounded d-none mt-2"
                                            style="height: auto; object-fit: contain;">
                                    </div>
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

                            <!-- Upload Foto Edit -->
                            <div class="mb-3">
                                <label class="form-label">Ganti Foto</label>

                                <div id="editUploadBox"
                                    class="bg-light w-100 d-flex flex-column align-items-center justify-content-center border rounded p-3 overflow-hidden"
                                    style="min-height: 180px; cursor: pointer;"
                                    onclick="document.getElementById('edit_foto_input').click();">

                                    <!-- Placeholder -->
                                    <div id="editUploadPlaceholder"
                                        class="d-flex flex-column align-items-center justify-content-center">

                                        <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                        <span class="text-muted">Klik untuk upload</span>
                                    </div>

                                    <!-- Input -->
                                    <input type="file"
                                        name="foto_ruangan"
                                        id="edit_foto_input"
                                        class="d-none"
                                        accept="image/*">

                                    <!-- Preview -->
                                    <img id="edit_preview"
                                        class="w-100 rounded d-none mt-2"
                                        style="height: auto; object-fit: contain;">
                                </div>
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
        <script>
            // PREVIEW FOTO TAMBAH RUANGAN
            const fotoInput = document.getElementById('fotoInput');
            const previewImg = document.getElementById('previewImg');
            const uploadPlaceholder = document.getElementById('uploadPlaceholder');

            fotoInput.addEventListener('change', function(e) {

                const file = e.target.files[0];

                if (file) {

                    // Validasi gambar
                    if (!file.type.startsWith('image/')) {
                        alert('File harus berupa gambar!');
                        fotoInput.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function(event) {

                        // tampilkan gambar
                        previewImg.src = event.target.result;
                        previewImg.classList.remove('d-none');

                        // sembunyikan placeholder
                        uploadPlaceholder.classList.add('d-none');
                    }

                    reader.readAsDataURL(file);
                }
            });

            // PREVIEW FOTO EDIT
            const editFotoInput = document.getElementById('edit_foto_input');
            const editPreview = document.getElementById('edit_preview');
            const editPlaceholder = document.getElementById('editUploadPlaceholder');

            editFotoInput.addEventListener('change', function(e) {

                const file = e.target.files[0];

                if (file) {

                    // validasi gambar
                    if (!file.type.startsWith('image/')) {
                        alert('File harus berupa gambar!');
                        editFotoInput.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function(event) {

                        // tampilkan preview baru
                        editPreview.src = event.target.result;
                        editPreview.classList.remove('d-none');

                        // sembunyikan placeholder
                        editPlaceholder.classList.add('d-none');
                    }

                    reader.readAsDataURL(file);
                }
            });

            // MODAL EDIT
            document.addEventListener("DOMContentLoaded", function() {

                const modal = document.getElementById('editRuanganModal');

                modal.addEventListener('show.bs.modal', function(event) {

                    let button = event.relatedTarget;

                    let id = button.getAttribute('data-id');
                    let nama = button.getAttribute('data-nama');
                    let kapasitas = button.getAttribute('data-kapasitas');
                    let tipe = button.getAttribute('data-tipe');
                    let foto = button.getAttribute('data-foto');

                    // isi form
                    document.getElementById('edit_id').value = id;
                    document.getElementById('edit_nama').value = nama;
                    document.getElementById('edit_kapasitas').value = kapasitas;

                    // Select tipe
                    tipe = tipe.trim().toLowerCase();
                    document.getElementById('edit_tipe').value = tipe;

                    // preview foto
                    // preview foto lama
                    const editPreview = document.getElementById('edit_preview');
                    const editPlaceholder = document.getElementById('editUploadPlaceholder');

                    if (foto && foto.trim() !== '') {

                        // tampilkan preview
                        editPreview.src = foto;
                        editPreview.classList.remove('d-none');

                        // sembunyikan placeholder
                        editPlaceholder.classList.add('d-none');

                    } else {

                        // reset preview
                        editPreview.src = '';
                        editPreview.classList.add('d-none');

                        // tampilkan placeholder
                        editPlaceholder.classList.remove('d-none');
                    }
                });

            });
        </script>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</div>