<?php
// require_once '../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();

$id_ruangan = $_GET['id_ruangan'] ?? null;

if (!$id_ruangan) {
    die("Ruangan tidak valid (missing id_ruangan)");
}

$ruanganModel = new \App\Models\Ruangan($db);
$ruangan = $ruanganModel->getById($id_ruangan);

if (!$ruangan) {
    die("Ruangan tidak ditemukan");
}

$roomName = $ruangan['nama_ruangan'];

$barangModel = new \App\Models\Barang($db);
$barangList = $barangModel->getByRuangan($id_ruangan);
?>

<div id="layoutSidenav_content">

    <link href="css/pinjam-custom.css" rel="stylesheet" />

    <main id="layout-static">

        <div class="container-fluid px-4">

            <h1 class="mt-4">
                Pinjam - <?= htmlspecialchars($roomName) ?>
            </h1>

            <div class="card mb-4">

                <!-- ================= CALENDAR ================= -->
                <div class="calendar-container">

                    <div class="calendar-header">

                        <button id="prevMonth">
                            &#10094;
                        </button>

                        <div class="calendar-title">

                            <select id="monthSelect"></select>

                            <select id="yearSelect"></select>

                        </div>

                        <button id="nextMonth">
                            &#10095;
                        </button>

                    </div>

                    <div class="calendar-days-header">

                        <div>Minggu</div>
                        <div>Senin</div>
                        <div>Selasa</div>
                        <div>Rabu</div>
                        <div>Kamis</div>
                        <div>Jumat</div>
                        <div>Sabtu</div>

                    </div>

                    <div id="calendarDates" class="calendar-grid"></div>

                </div>

                <!-- ================= MODAL ================= -->
                <div id="loanModal" class="modal">

                    <div class="modal-content large-modal">

                        <span class="close">
                            &times;
                        </span>

                        <div class="text-center mb-3">

                            <h4 class="fw-semibold text-primary">
                                <?= htmlspecialchars($roomName) ?>
                            </h4>

                        </div>

                        <form id="formPeminjaman"
                            action="controllers/PeminjamanController.php?action=create"
                            method="POST"
                            enctype="multipart/form-data"
                            class="row g-3">

                            <input type="hidden"
                                name="ruangan"
                                value="<?= $id_ruangan ?>">

                            <!-- ================= KIRI ================= -->
                            <div class="col-12 col-lg-8">

                                <!-- JENIS -->
                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        JENIS PEMINJAMAN
                                    </label>

                                    <select class="form-select"
                                        name="jenis_peminjaman"
                                        id="jenisPeminjaman"
                                        required>

                                        <option value="">
                                            Pilih
                                        </option>

                                        <option value="barang">
                                            Barang
                                        </option>

                                        <option value="ruangan">
                                            Ruangan
                                        </option>

                                    </select>

                                </div>

                                <!-- NAMA -->
                                <div class="row g-3 mb-3">

                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Nama
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            value="<?= $_SESSION['user']['nama'] ?? '' ?>"
                                            disabled>

                                    </div>

                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Keperluan
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            name="keperluan"
                                            required>

                                    </div>

                                </div>

                                <!-- WAKTU -->
                                <div class="row g-3 mb-3">

                                    <div class="col-md-6">

                                        <label>
                                            Mulai
                                        </label>

                                        <input type="datetime-local"
                                            class="form-control"
                                            name="waktu_mulai"
                                            required>

                                    </div>

                                    <div class="col-md-6">

                                        <label>
                                            Selesai
                                        </label>

                                        <input type="datetime-local"
                                            class="form-control"
                                            name="waktu_selesai"
                                            required>

                                    </div>

                                </div>

                                <!-- ================= BARANG ================= -->
                                <div class="mb-3">

                                    <label class="fw-bold">
                                        Daftar Barang
                                    </label>

                                    <table id="tableBarang"
                                        class="table table-sm table-bordered opacity-50 bg-light border-secondary">

                                        <thead>

                                            <tr>

                                                <th class="text-center">

                                                    <input type="checkbox"
                                                        id="selectAll"
                                                        disabled>

                                                </th>

                                                <th>ID</th>

                                                <th>Nama</th>

                                                <th>Jumlah</th>

                                                <th>Stok</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php if (!empty($barangList)): ?>

                                                <?php foreach ($barangList as $b): ?>

                                                    <tr>

                                                        <td class="text-center">

                                                            <input type="checkbox"
                                                                class="barang-checkbox"
                                                                name="barang[<?= $b['id_barang'] ?>][checked]"
                                                                disabled>

                                                        </td>

                                                        <td>
                                                            <?= $b['id_barang'] ?>
                                                        </td>

                                                        <td>
                                                            <?= htmlspecialchars($b['nama_barang']) ?>
                                                        </td>

                                                        <td>

                                                            <input type="number"
                                                                min="1"
                                                                value="1"
                                                                name="barang[<?= $b['id_barang'] ?>][kuantitas]"
                                                                class="form-control form-control-sm jumlah-input"
                                                                disabled>

                                                        </td>

                                                        <td class="stok-value">
                                                            <?= $b['stok_tersedia'] ?? 0 ?>
                                                        </td>

                                                    </tr>

                                                <?php endforeach; ?>

                                            <?php else: ?>

                                                <tr>

                                                    <td colspan="5"
                                                        class="text-center text-muted">

                                                        Tidak ada barang di ruangan ini

                                                    </td>

                                                </tr>

                                            <?php endif; ?>

                                        </tbody>

                                    </table>

                                </div>

                                <!-- CATATAN -->
                                <div class="mb-3">

                                    <label>
                                        Catatan
                                    </label>

                                    <textarea class="form-control"
                                        name="catatan"
                                        style="height: 115px;"></textarea>

                                </div>

                            </div>

                            <!-- ================= KANAN ================= -->
                            <div class="col-lg-4">

                                <!-- FOTO -->
                                <div class="mb-3 text-center">

                                    <label class="form-label fw-bold">
                                        Foto Ruangan
                                    </label>

                                    <div>

                                        <img src="<?= !empty($ruangan['foto_ruangan'])
                                                        ? $ruangan['foto_ruangan']
                                                        : 'assets/img/no-image.png' ?>"
                                            class="img-fluid rounded shadow-sm"
                                            style="max-height: 330px; object-fit: cover;"
                                            alt="Foto Ruangan">

                                    </div>

                                </div>

                                <!-- JAMINAN -->
                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Jaminan
                                    </label>

                                    <input type="file"
                                        name="jaminan"
                                        class="form-control"
                                        required>

                                    <div class="invalid-feedback">

                                        Foto jaminan wajib diisi.

                                    </div>

                                </div>

                                <!-- BUTTON -->
                                <button type="submit"
                                    class="btn btn-primary w-100">

                                    Kirim

                                </button>

                            </div>

                            <!-- KETENTUAN -->
                            <div style="margin-top: 5px;">

                                <span class="text-muted small">

                                    Lihat
                                    <a href="#"
                                        class="text-decoration-none text-primary">

                                        Ketentuan Peminjaman

                                    </a>

                                </span>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

<!-- ================= SCRIPT ================= -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const form =
            document.getElementById('formPeminjaman');

        const tableBarang =
            document.getElementById('tableBarang');

        const selectAll =
            document.getElementById('selectAll');

        const jenisPeminjaman =
            document.getElementById('jenisPeminjaman');

        const barangCheckboxes =
            document.querySelectorAll('.barang-checkbox');

        const jumlahInputs =
            document.querySelectorAll('.jumlah-input');

        // =========================
        // SELECT ALL
        // =========================
        selectAll.addEventListener('change', function() {

            barangCheckboxes.forEach(cb => {

                if (!cb.disabled) {

                    cb.checked = this.checked;

                }

            });

        });

        // =========================
        // JENIS PEMINJAMAN
        // =========================
        jenisPeminjaman.addEventListener('change', function() {

            const value = this.value;

            // =====================
            // PEMINJAMAN RUANGAN
            // =====================
            if (value === 'ruangan') {

                // table disabled style
                tableBarang.classList.add(
                    'opacity-50',
                    'bg-light',
                    'border-secondary'
                );

                barangCheckboxes.forEach(cb => {

                    cb.checked = true;
                    cb.disabled = true;

                });

                jumlahInputs.forEach(input => {

                    let stok =
                        input.closest('tr')
                        .querySelector('.stok-value')
                        .innerText.trim();

                    // jumlah otomatis = stok
                    input.value = stok;

                    // disable jumlah
                    input.disabled = true;

                });

                selectAll.checked = true;
                selectAll.disabled = true;

            }

            // =====================
            // PEMINJAMAN BARANG
            // =====================
            else if (value === 'barang') {

                // table normal
                tableBarang.classList.remove(
                    'opacity-50',
                    'bg-light',
                    'border-secondary'
                );

                barangCheckboxes.forEach(cb => {

                    cb.disabled = false;
                    cb.checked = false;

                });

                jumlahInputs.forEach(input => {

                    input.value = 1;
                    input.disabled = false;

                });

                selectAll.disabled = false;
                selectAll.checked = false;

            }

            // =====================
            // DEFAULT
            // =====================
            else {

                // table disabled style
                tableBarang.classList.add(
                    'opacity-50',
                    'bg-light',
                    'border-secondary'
                );

                barangCheckboxes.forEach(cb => {

                    cb.checked = false;
                    cb.disabled = true;

                });

                jumlahInputs.forEach(input => {

                    input.value = 1;
                    input.disabled = true;

                });

                selectAll.checked = false;
                selectAll.disabled = true;

            }

        });

        // =========================
        // VALIDASI SUBMIT
        // =========================
        form.addEventListener('submit', function(e) {

            const jenis =
                jenisPeminjaman.value;

            // validasi hanya untuk barang
            if (jenis === 'barang') {

                const checked =
                    document.querySelectorAll(
                        '.barang-checkbox:checked'
                    );

                // minimal 1 barang
                if (checked.length < 1) {

                    e.preventDefault();

                    alert(
                        'Pilih minimal 1 barang untuk dipinjam.'
                    );

                    return;

                }

            }

        });

    });
</script>