<?php
// require_once '../config/Autoloader.php';
// hallo dunia
// hallo dunia dua
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

            <h1 class="mt-4">Pinjam - <?= htmlspecialchars($roomName) ?></h1>

            <div class="card mb-4">

                <!-- ================= CALENDAR ================= -->
                <div class="calendar-container">
                    <div class="calendar-header">
                        <button id="prevMonth">&#10094;</button>

                        <div class="calendar-title">
                            <select id="monthSelect"></select>
                            <select id="yearSelect"></select>
                        </div>

                        <button id="nextMonth">&#10095;</button>
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

                <!--  MODAL  -->
                <div id="loanModal" class="modal">
                    <div class="modal-content large-modal">
                        <span class="close">&times;</span>

                        <div class="text-center mb-3">
                            <h4 class="fw-semibold text-primary">
                                <?= htmlspecialchars($roomName) ?>
                            </h4>
                        </div>

                        <form action="controllers/PeminjamanController.php?action=create"
                            method="POST"
                            enctype="multipart/form-data"
                            class="row g-3">

                            <input type="hidden" name="ruangan" value="<?= $id_ruangan ?>">

                            <div class="col-12 col-lg-8">

                                <!-- JENIS -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">JENIS PEMINJAMAN</label>
                                    <select class="form-select" name="jenis_peminjaman" required>
                                        <option value="">Pilih</option>
                                        <option value="barang">Barang</option>
                                        <option value="ruangan">Ruangan</option>
                                    </select>
                                </div>

                                <!-- NAMA -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama</label>
                                        <input type="text" class="form-control"
                                            value="<?= $_SESSION['user']['nama'] ?? '' ?>" disabled>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Keperluan</label>
                                        <input type="text" class="form-control"
                                            name="keperluan" required>
                                    </div>
                                </div>

                                <!-- WAKTU -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label>Mulai</label>
                                        <input type="datetime-local" class="form-control"
                                            name="waktu_mulai" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Selesai</label>
                                        <input type="datetime-local" class="form-control"
                                            name="waktu_selesai" required>
                                    </div>
                                </div>

                                <!-- BARANG -->
                                <div class="mb-3">
                                    <label class="fw-bold">Daftar Barang</label>

                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
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
                                                        <td>
                                                            <input type="checkbox"
                                                                name="barang[<?= $b['id_barang'] ?>][checked]">
                                                        </td>

                                                        <td><?= $b['id_barang'] ?></td>

                                                        <td><?= htmlspecialchars($b['nama_barang']) ?></td>

                                                        <td>
                                                            <input type="number" min="1" value="1"
                                                                name="barang[<?= $b['id_barang'] ?>][kuantitas]"
                                                                class="form-control form-control-sm">
                                                        </td>

                                                        <td><?= $b['stok_tersedia'] ?? 0 ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">
                                                        Tidak ada barang di ruangan ini
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- CATATAN -->
                                <div class="mb-3">
                                    <label>Catatan</label>
                                    <textarea class="form-control" name="catatan" style="height: 115px;"></textarea>
                                </div>

                            </div>

                            <!-- KANAN -->
                            <div class="col-lg-4">

                                <div class="mb-3 text-center">
                                    <label class="form-label fw-bold">Foto Ruangan</label>
                                    <div>
                                        <img src="<?= !empty($ruangan['foto_ruangan']) ? $ruangan['foto_ruangan'] : 'assets/img/error-404-monochrome.svg' ?>"
                                            class="img-fluid rounded shadow-sm"
                                            style="max-height: 330px; object-fit: cover;">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jaminan</label>

                                    <input type="file"
                                        name="jaminan"
                                        class="form-control"
                                        required>

                                    <div class="invalid-feedback">
                                        Foto jaminan wajib diisi.
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    Kirim
                                </button>
                            </div>

                            <div style="margin-top: 5px;">
                                <span class="text-muted small">
                                    Lihat <a href="#" class="text-decoration-none text-primary">Ketentuan Peminjaman</a>
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
        const selectAll = document.getElementById('selectAll');

        if (selectAll) {
            selectAll.addEventListener('click', function() {
                document.querySelectorAll('input[type=checkbox]').forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        }
    });
</script>