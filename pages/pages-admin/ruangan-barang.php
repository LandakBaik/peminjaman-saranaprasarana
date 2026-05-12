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
            <h1 class="mt-4">Ruangan & Barang</h1>

            <!-- Action -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">
                    Kelola data ruangan dan inventaris barang secara terpadu
                </span>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-white border border-primary text-primary btn-sm"
                        onclick="checkExportRuangan()">
                        <i class="fas fa-download me-1"></i>
                        Export
                    </button>

                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#tambahRuanganModal">
                        <i class="fas fa-plus me-1"></i>
                        Tambah Ruangan
                    </button>
                </div>
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
                                    <th><input type="checkbox" id="selectAllRuangan"></th>
                                    <th>No</th>
                                    <th>Nama Ruangan</th>
                                    <th>Kapasitas</th>
                                    <th>Total Aset</th>
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
                                        <td class="text-center"><input type="checkbox" class="export-checkbox"
                                                value="<?= $row['id_ruangan'] ?>"></td>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><strong><?= htmlspecialchars($row['nama_ruangan']) ?></strong></td>
                                        <td class="text-center"><?= htmlspecialchars($row['kapasitas']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">
                                                <?= $row['total_barang'] ?> Item
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info text-dark">
                                                <?= ucfirst($row['tipe_ruangan']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?= date('d M Y', strtotime($row['tanggal_dibuat'])) ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-info btn-sm text-white"
                                                onclick="openAsetModal(<?= $row['id_ruangan'] ?>, '<?= htmlspecialchars($row['nama_ruangan'], ENT_QUOTES) ?>')">
                                                <i class="fas fa-box me-1"></i>
                                                Aset
                                            </button>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editRuanganModal" data-id="<?= $row['id_ruangan'] ?>"
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
        <div class="modal fade" id="tambahRuanganModal" tabindex="-1" aria-labelledby="tambahRuanganModalLabel"
            aria-hidden="true">
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

                        <form action="controllers/RuanganController.php?action=create" method="POST"
                            enctype="multipart/form-data">
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
                                        <input type="file" name="foto_ruangan" id="fotoInput" class="d-none"
                                            accept="image/*">

                                        <!-- Preview -->
                                        <img id="previewImg" class="w-100 rounded d-none mt-2"
                                            style="height: auto; object-fit: contain;">
                                    </div>
                                </div>

                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3">
                                <span class="text-muted small">
                                    Lihat <a href="index.php?page=ketentuan"
                                        class="text-decoration-none text-primary">Ketentuan Peminjaman</a>
                                </span>

                                <div>
                                    <button type="button" class="btn btn-outline-secondary me-2"
                                        data-bs-dismiss="modal">Kembali</button>
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

                        <form action="controllers/RuanganController.php?action=update" method="POST"
                            enctype="multipart/form-data">

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
                                    <input type="file" name="foto_ruangan" id="edit_foto_input" class="d-none"
                                        accept="image/*">

                                    <!-- Preview -->
                                    <img id="edit_preview" class="w-100 rounded d-none mt-2"
                                        style="height: auto; object-fit: contain;">
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3">
                                <span class="text-muted small">
                                    Lihat <a href="index.php?page=ketentuan"
                                        class="text-decoration-none text-primary">Ketentuan Peminjaman</a>
                                </span>

                                <div>
                                    <button type="button" class="btn btn-outline-secondary me-2"
                                        data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-warning">Kirim</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Kelola Aset -->
        <div class="modal fade" id="asetRuanganModal" tabindex="-1">

            <div class="modal-dialog modal-xl modal-dialog-centered">

                <div class="modal-content border-0 shadow rounded-4">

                    <!-- Header -->
                    <div class="modal-header border-0 pb-0">

                        <div>

                            <h5 class="modal-title fw-bold mb-1">
                                Daftar Aset
                            </h5>

                            <small class="text-muted">
                                <span id="modal_nama_ruangan"></span>
                            </small>

                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <!-- Body -->
                    <div class="modal-body pt-3">

                        <!-- Top -->
                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Kelola Barang
                                </h6>

                                <small class="text-muted">
                                    Inventaris barang pada ruangan ini
                                </small>

                            </div>

                            <button class="btn btn-primary btn-sm px-3" onclick="openTambahAset()">

                                <i class="fas fa-plus me-1"></i>
                                Tambah Barang

                            </button>

                        </div>

                        <!-- Table -->
                        <div class="table-responsive">

                            <table class="table align-middle" id="tableAset">

                                <thead class="table-light">

                                    <tr>

                                        <th>Nama Barang</th>

                                        <th class="text-center">
                                            Total
                                        </th>

                                        <th class="text-center">
                                            Rusak
                                        </th>

                                        <th class="text-center">
                                            Tersedia
                                        </th>

                                        <th class="text-center">
                                            Aksi
                                        </th>

                                    </tr>

                                </thead>

                                <tbody id="bodyAset">
                                    <!-- Dynamic -->
                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Modal Tambah Aset -->
        <div class="modal fade" id="tambahAsetModal" tabindex="-1">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content border-0 shadow rounded-4">

                    <div class="modal-header border-0 pb-0">

                        <div>

                            <h5 class="modal-title fw-bold mb-1">
                                Tambah Barang
                            </h5>

                            <small class="text-muted">
                                Tambahkan barang baru ke ruangan
                            </small>

                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <form action="controllers/BarangController.php?action=create" method="POST">

                        <input type="hidden" name="source" value="ruangan">

                        <input type="hidden" name="id_ruangan" id="tambah_aset_id_ruangan">

                        <div class="modal-body pt-3">

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Nama Barang
                                </label>

                                <input type="text" class="form-control" name="nama_barang" required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Deskripsi
                                </label>

                                <textarea class="form-control" name="deskripsi_barang" rows="3"></textarea>

                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Total Stok
                                </label>

                                <input type="number" class="form-control" name="total_stok" min="1" required>

                            </div>

                        </div>

                        <div class="modal-footer border-0 pt-0">

                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">

                                Batal

                            </button>

                            <button type="submit" class="btn btn-primary">

                                Simpan Barang

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- Modal Edit Aset -->
        <div class="modal fade" id="editAsetModal" tabindex="-1">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content border-0 shadow rounded-4">

                    <div class="modal-header border-0 pb-0">

                        <div>

                            <h5 class="modal-title fw-bold mb-1">
                                Edit Barang
                            </h5>

                            <small class="text-muted">
                                Perbarui data barang
                            </small>

                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <form action="controllers/BarangController.php?action=update" method="POST">

                        <input type="hidden" name="source" value="ruangan">

                        <input type="hidden" name="id_barang" id="edit_aset_id_barang">

                        <input type="hidden" name="id_ruangan" id="edit_aset_id_ruangan">

                        <div class="modal-body pt-3">

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Nama Barang
                                </label>

                                <input type="text" class="form-control" name="nama_barang" id="edit_aset_nama" required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Deskripsi
                                </label>

                                <textarea class="form-control" name="deskripsi_barang" id="edit_aset_deskripsi"
                                    rows="3"></textarea>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">
                                        Total Stok
                                    </label>

                                    <input type="number" class="form-control" name="total_stok" id="edit_aset_total"
                                        min="1" required>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">
                                        Stok Rusak
                                    </label>

                                    <input type="number" class="form-control" name="stok_rusak" id="edit_aset_rusak"
                                        min="0" required>

                                </div>

                            </div>

                        </div>

                        <div class="modal-footer border-0 pt-0">

                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">

                                Batal

                            </button>

                            <button type="submit" class="btn btn-primary">

                                Simpan Perubahan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <script>

            let currentRoomId = null;
            let currentRoomName = '';

            function openAsetModal(id, nama) {

                currentRoomId = id;
                currentRoomName = nama;

                document.getElementById('modal_nama_ruangan')
                    .textContent = nama;

                fetch(
                    `controllers/BarangController.php?action=list_by_ruangan&id_ruangan=${id}`
                )

                    .then(res => res.json())

                    .then(data => {

                        const body =
                            document.getElementById('bodyAset');

                        body.innerHTML = '';

                        if (data.length === 0) {

                            body.innerHTML = `
                <tr>
                    <td colspan="5"
                        class="text-center text-muted py-4">

                        Belum ada barang di ruangan ini

                    </td>
                </tr>
            `;

                        } else {

                            data.forEach(item => {

                                body.innerHTML += `
                    <tr>

                        <td>

                            <div class="fw-semibold">
                                ${item.nama_barang}
                            </div>

                            <small class="text-muted">
                                ${item.deskripsi_barang || '-'}
                            </small>

                        </td>

                        <td class="text-center">
                            ${item.total_stok}
                        </td>

                        <td class="text-center">
                            ${item.stok_rusak}
                        </td>

                        <td class="text-center">

                            <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">

                                ${item.stok_tersedia}

                            </span>

                        </td>

                        <td class="text-center">

                            <button class="btn btn-light border btn-sm"
                                onclick='openEditAset(${JSON.stringify(item)})'>

                                <i class="fas fa-edit"></i>

                            </button>

                            <a href="controllers/BarangController.php?action=delete&id_barang=${item.id_barang}&source=ruangan"
                                class="btn btn-light border border-danger text-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus barang ini?')">

                                <i class="fas fa-trash"></i>

                            </a>

                        </td>

                    </tr>
                `;
                            });

                        }

                        new bootstrap.Modal(
                            document.getElementById('asetRuanganModal')
                        ).show();

                    });

            }

            function openTambahAset() {

                document.getElementById('tambah_aset_id_ruangan')
                    .value = currentRoomId;

                new bootstrap.Modal(
                    document.getElementById('tambahAsetModal')
                ).show();

            }

            function openEditAset(item) {

                document.getElementById('edit_aset_id_barang')
                    .value = item.id_barang;

                document.getElementById('edit_aset_id_ruangan')
                    .value = item.id_ruangan;

                document.getElementById('edit_aset_nama')
                    .value = item.nama_barang;

                document.getElementById('edit_aset_deskripsi')
                    .value = item.deskripsi_barang;

                document.getElementById('edit_aset_total')
                    .value = item.total_stok;

                document.getElementById('edit_aset_rusak')
                    .value = item.stok_rusak;

                new bootstrap.Modal(
                    document.getElementById('editAsetModal')
                ).show();

            }

            // Export Data Ruangan
            function checkExportRuangan() {

                let checked = document.querySelectorAll(
                    '.export-checkbox:checked'
                );

                if (checked.length === 0) {

                    alert('Anda perlu memilih minimal 1 ruangan untuk membuat laporan.');

                    return;
                }

                let form = document.createElement('form');
                form.method = 'POST';
                form.action = 'controllers/RuanganController.php?action=export';

                checked.forEach(function (checkbox) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'id_ruangan[]';
                    input.value = checkbox.value;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
                setTimeout(() => document.body.removeChild(form), 1000);
            }

            // Add Select All functionality
            document.getElementById('selectAllRuangan')?.addEventListener('change', function () {
                let checkboxes = document.querySelectorAll('.export-checkbox');
                for (let checkbox of checkboxes) {
                    checkbox.checked = this.checked;
                }
            });
            // PREVIEW FOTO TAMBAH RUANGAN
            const fotoInput = document.getElementById('fotoInput');
            const previewImg = document.getElementById('previewImg');
            const uploadPlaceholder = document.getElementById('uploadPlaceholder');

            fotoInput.addEventListener('change', function (e) {

                const file = e.target.files[0];

                if (file) {

                    // Validasi gambar
                    if (!file.type.startsWith('image/')) {
                        alert('File harus berupa gambar!');
                        fotoInput.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function (event) {

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

            editFotoInput.addEventListener('change', function (e) {

                const file = e.target.files[0];

                if (file) {

                    // validasi gambar
                    if (!file.type.startsWith('image/')) {
                        alert('File harus berupa gambar!');
                        editFotoInput.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function (event) {

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
            document.addEventListener("DOMContentLoaded", function () {

                const modal = document.getElementById('editRuanganModal');

                modal.addEventListener('show.bs.modal', function (event) {

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