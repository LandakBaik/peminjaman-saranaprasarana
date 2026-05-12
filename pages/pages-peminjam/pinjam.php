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
                                            id="waktu_mulai"
                                            class="form-control"
                                            name="waktu_mulai"
                                            required>

                                    </div>

                                    <div class="col-md-6">

                                        <label>
                                            Selesai
                                        </label>

                                        <input type="datetime-local"
                                            id="waktu_selesai"
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

                                                <th class="text-center" style="width: 50px;">
                                                    <input type="checkbox" id="selectAll" disabled>
                                                </th>
                                                <th class="text-center" style="width: 60px;">ID</th>
                                                <th>Nama Barang</th>
                                                <th class="text-center" style="width: 100px;">Jumlah</th>
                                                <th class="text-center" style="width: 80px;">Stok</th>
                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php if (!empty($barangList)): ?>

                                                <?php foreach ($barangList as $b): ?>

                                                    <tr>
                                                        <td class="text-center">
                                                            <input type="checkbox" class="barang-checkbox" name="barang[<?= $b['id_barang'] ?>][checked]" disabled>
                                                        </td>
                                                        <td class="text-center"><?= $b['id_barang'] ?></td>
                                                        <td><?= htmlspecialchars($b['nama_barang']) ?></td>
                                                        <td class="text-center">
                                                            <input type="number" min="1" value="1" name="barang[<?= $b['id_barang'] ?>][kuantitas]" class="form-control form-control-sm jumlah-input mx-auto" style="width: 70px;" disabled>
                                                        </td>
                                                        <td class="stok-value text-center"><?= $b['stok_tersedia'] ?? 0 ?></td>
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
                                    <a href="index.php?page=ketentuan"
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

        const form            = document.getElementById('formPeminjaman');
        const tableBarang     = document.getElementById('tableBarang');
        const selectAll       = document.getElementById('selectAll');
        const jenisPeminjaman = document.getElementById('jenisPeminjaman');
        const inputMulai      = document.getElementById('waktu_mulai');
        const inputSelesai    = document.getElementById('waktu_selesai');
        const idRuangan       = '<?= $id_ruangan ?>';

        // Stok awal dari server (sebelum ada input waktu)
        const stokAwal = {};
        document.querySelectorAll('#tableBarang tbody tr').forEach(row => {
            const stokCell = row.querySelector('.stok-value');
            const cb       = row.querySelector('.barang-checkbox');
            if (stokCell && cb) {
                const idBarang = cb.name.match(/barang\[(\d+)\]/)?.[1];
                if (idBarang) stokAwal[idBarang] = parseInt(stokCell.dataset.stokAwal ?? stokCell.innerText.trim(), 10);
                stokCell.dataset.stokAwal = stokCell.innerText.trim(); // simpan nilai awal
            }
        });

        // =========================
        // CEK STOK REAL-TIME (AJAX)
        // =========================
        let ajaxTimer = null;

        function checkStock() {
            const mulai   = inputMulai.value;
            const selesai = inputSelesai.value;

            // Hanya jalankan jika kedua waktu sudah terisi & valid
            if (!mulai || !selesai || mulai >= selesai) {
                // Reset ke stok awal
                resetToStokAwal();
                return;
            }

            clearTimeout(ajaxTimer);
            ajaxTimer = setTimeout(() => {
                const url = `controllers/PeminjamanController.php?action=check_stock` +
                            `&id_ruangan=${encodeURIComponent(idRuangan)}` +
                            `&waktu_mulai=${encodeURIComponent(mulai)}` +
                            `&waktu_selesai=${encodeURIComponent(selesai)}`;

                fetch(url)
                    .then(r => r.json())
                    .then(json => {
                        if (!json.success) return;

                        json.data.forEach(item => {
                            const idBarang = String(item.id_barang);
                            const stok     = item.stok_tersedia;

                            // Cari baris berdasarkan id barang
                            const cb = document.querySelector(
                                `.barang-checkbox[name="barang[${idBarang}][checked]"]`
                            );
                            if (!cb) return;

                            const row       = cb.closest('tr');
                            const stokCell  = row.querySelector('.stok-value');
                            const jumlahInput = row.querySelector('.jumlah-input');

                            // Update tampilan stok
                            stokCell.textContent = stok;

                            if (stok <= 0) {
                                // Tandai baris tidak tersedia
                                row.classList.add('table-danger');
                                row.classList.remove('table-warning');
                                cb.checked  = false;
                                cb.disabled = true;
                                jumlahInput.disabled = true;
                            } else if (item.terpinjam > 0) {
                                // Ada sebagian dipinjam, beri peringatan
                                row.classList.add('table-warning');
                                row.classList.remove('table-danger');
                                cb.disabled = (jenisPeminjaman.value === 'barang') ? false : true;
                                // Batasi max jumlah sesuai stok tersedia
                                jumlahInput.max = stok;
                                if (parseInt(jumlahInput.value) > stok) {
                                    jumlahInput.value = stok;
                                }
                            } else {
                                row.classList.remove('table-danger', 'table-warning');
                                cb.disabled = (jenisPeminjaman.value === 'barang') ? false : true;
                                jumlahInput.max = stok;
                            }
                        });
                    })
                    .catch(err => console.warn('checkStock error:', err));
            }, 400); // debounce 400ms
        }

        function resetToStokAwal() {
            document.querySelectorAll('#tableBarang tbody tr').forEach(row => {
                const stokCell  = row.querySelector('.stok-value');
                const cb        = row.querySelector('.barang-checkbox');
                if (!stokCell) return;
                const stokOri   = stokCell.dataset.stokAwal ?? stokCell.innerText.trim();
                stokCell.textContent = stokOri;
                row.classList.remove('table-danger', 'table-warning');
                if (cb && jenisPeminjaman.value === 'barang') cb.disabled = false;
            });
        }

        // Pasang listener ke kedua input waktu
        inputMulai.addEventListener('change', checkStock);
        inputSelesai.addEventListener('change', checkStock);

        // =========================
        // SELECT ALL
        // =========================
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.barang-checkbox').forEach(cb => {
                if (!cb.disabled) cb.checked = this.checked;
            });
        });

        // =========================
        // JENIS PEMINJAMAN
        // =========================
        jenisPeminjaman.addEventListener('change', function() {

            const value = this.value;

            if (value === 'ruangan') {

                tableBarang.classList.add('opacity-50', 'bg-light', 'border-secondary');

                document.querySelectorAll('.barang-checkbox').forEach(cb => {
                    cb.checked  = true;
                    cb.disabled = true;
                });

                document.querySelectorAll('.jumlah-input').forEach(input => {
                    let stok = input.closest('tr').querySelector('.stok-value').innerText.trim();
                    input.value    = stok;
                    input.disabled = true;
                });

                selectAll.checked  = true;
                selectAll.disabled = true;

            } else if (value === 'barang') {

                tableBarang.classList.remove('opacity-50', 'bg-light', 'border-secondary');

                document.querySelectorAll('.barang-checkbox').forEach(cb => {
                    // Jangan enable baris yang stoknya 0
                    const stok = parseInt(cb.closest('tr').querySelector('.stok-value').innerText.trim(), 10);
                    cb.disabled = (stok <= 0);
                    cb.checked  = false;
                });

                document.querySelectorAll('.jumlah-input').forEach(input => {
                    input.value    = 1;
                    input.disabled = false;
                });

                selectAll.disabled = false;
                selectAll.checked  = false;

            } else {

                tableBarang.classList.add('opacity-50', 'bg-light', 'border-secondary');

                document.querySelectorAll('.barang-checkbox').forEach(cb => {
                    cb.checked  = false;
                    cb.disabled = true;
                });

                document.querySelectorAll('.jumlah-input').forEach(input => {
                    input.value    = 1;
                    input.disabled = true;
                });

                selectAll.checked  = false;
                selectAll.disabled = true;
            }
        });

        // =========================
        // TAMPILKAN ERROR SERVER
        // =========================
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error') === 'stok_kurang') {
            const namaBarang = urlParams.get('barang') ?? 'barang yang dipilih';
            alert(`❌ Stok tidak mencukupi untuk "${namaBarang}" pada rentang waktu yang dipilih. Silakan kurangi jumlah atau ubah waktu peminjaman.`);
        }

        // =========================
        // VALIDASI SUBMIT
        // =========================
        form.addEventListener('submit', function(e) {

            if (jenisPeminjaman.value === 'barang') {

                const checked = document.querySelectorAll('.barang-checkbox:checked');

                if (checked.length < 1) {
                    e.preventDefault();
                    alert('Pilih minimal 1 barang untuk dipinjam.');
                    return;
                }

                // Cek apakah ada barang yang dipilih tapi stoknya 0
                let adaStokKurang = false;
                checked.forEach(cb => {
                    const row  = cb.closest('tr');
                    const stok = parseInt(row.querySelector('.stok-value').innerText.trim(), 10);
                    const qty  = parseInt(row.querySelector('.jumlah-input').value, 10);
                    if (qty > stok || stok <= 0) adaStokKurang = true;
                });

                if (adaStokKurang) {
                    e.preventDefault();
                    alert('❌ Salah satu barang yang dipilih melebihi stok tersedia pada rentang waktu tersebut.');
                    return;
                }
            }
        });

    });
</script>