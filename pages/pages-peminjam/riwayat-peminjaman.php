<?php
// View variables are provided by PageController
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <!-- Title -->
            <h1 class="mt-4 fw-bold">Riwayat Peminjaman</h1>

            <!-- Action -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Daftar peminjaman yang telah selesai atau ditolak</span>
                <a href="index.php?page=peminjaman-saya" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
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
                                    <!-- Status -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select class="form-select" id="filterStatus">
                                            <option value="" selected>Semua</option>
                                            <option value="Selesai">Selesai</option>
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
                        <table class="table table-bordered align-middle" id="peminjamanTable">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>No</th>
                                    <th>Kode Peminjaman</th>
                                    <th>Keperluan</th>
                                    <th>Status</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="peminjamanBody">
                                <?php
                                $no = 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $displayStatus = $row['status'];

                                    $statusClass = 'bg-secondary-subtle text-secondary';
                                    if (strtolower($displayStatus) == 'rejected' || strtolower($displayStatus) == 'ditolak')
                                        $statusClass = 'bg-danger-subtle text-danger';
                                    if (strtolower($displayStatus) == 'returned' || strtolower($displayStatus) == 'dikembalikan' || strtolower($displayStatus) == 'selesai')
                                        $statusClass = 'bg-success-subtle text-success';
                                    ?>
                                    <tr data-status="<?= ucfirst(htmlspecialchars($displayStatus)) ?>"
                                    data-type="<?= ucfirst(htmlspecialchars($row['jenis_peminjaman'])) ?>"
                                    data-date="<?= htmlspecialchars(date('Y-m-d', strtotime($row['tanggal_dibuat'] ?? 'now'))) ?>"
                                    data-room="<?= htmlspecialchars($row['nama_ruangan'] ?? '-') ?>">
                                        <td class="text-center"><input type="checkbox" class="row-checkbox"></td>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <?php
                                            $tgl = date('dmY', strtotime($row['tanggal_dibuat']));
                                            $kode = "PJM-" . $tgl . "-" . $row['id_peminjaman'];
                                            ?>
                                            <strong><?= htmlspecialchars($kode) ?></strong><br>
                                            <small
                                                class="text-muted"><?= htmlspecialchars($row['nama_ruangan'] ?? '-') ?></small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($row['keperluan']) ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge <?= $statusClass ?>"><?= ucfirst(htmlspecialchars($displayStatus)) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?= htmlspecialchars(date('d M Y - H:i', strtotime($row['waktu_mulai']))) ?>
                                        </td>

                                        <td class="text-center">
                                            <?= htmlspecialchars(date('d M Y - H:i', strtotime($row['waktu_selesai']))) ?>
                                        </td>
                                        <td class="text-center">
                                            <?= htmlspecialchars(date('d M Y - H:i', strtotime($row['tanggal_dibuat']))) ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-info btn-sm text-white btn-detail" data-bs-toggle="modal"
                                                data-bs-target="#detailModal" data-kode="<?= htmlspecialchars($kode) ?>"
                                                data-ruangan="<?= htmlspecialchars($row['nama_ruangan'] ?? '-') ?>"
                                                data-jenis="<?= htmlspecialchars(ucfirst($row['jenis_peminjaman'])) ?>"
                                                data-mulai="<?= htmlspecialchars($row['waktu_mulai']) ?>"
                                                data-selesai="<?= htmlspecialchars($row['waktu_selesai']) ?>"
                                                data-keperluan="<?= htmlspecialchars($row['keperluan']) ?>"
                                                data-catatan="<?= htmlspecialchars($row['catatan'] ?? '-') ?>"
                                                data-jaminan="<?= htmlspecialchars($row['jaminan'] ?? '') ?>"
                                                data-barang="<?= htmlspecialchars($row['daftar_barang'] ?? '-') ?>"
                                                data-status="<?= htmlspecialchars(ucfirst($displayStatus)) ?>"
                                                data-approval="<?= htmlspecialchars($row['staff_approval'] ?? '-') ?>"
                                                data-keterangan="<?= htmlspecialchars($row['keterangan'] ?? '') ?>">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if ($no == 1): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-3">Tidak ada riwayat peminjaman.</td>
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

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">

            <!-- Header -->
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold mb-0">
                        Detail Peminjaman
                    </h5>

                    <small class="text-muted">
                        Informasi lengkap riwayat peminjaman
                    </small>
                </div>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">

                <div class="row g-4">

                    <!-- LEFT -->
                    <div class="col-lg-8">

                        <!-- Informasi -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-start mb-4">

                                    <div>
                                        <h6 class="fw-bold text-primary mb-1">
                                            Informasi Peminjaman
                                        </h6>

                                        <small class="text-muted">
                                            Detail data peminjaman
                                        </small>
                                    </div>

                                    <span class="badge rounded-pill px-3 py-2"
                                        id="det-status"></span>

                                </div>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="text-muted small">
                                            Kode
                                        </label>

                                        <div class="fw-semibold"
                                            id="det-kode"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-muted small">
                                            Jenis
                                        </label>

                                        <div class="fw-semibold"
                                            id="det-jenis"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-muted small">
                                            Ruangan
                                        </label>

                                        <div class="fw-semibold"
                                            id="det-ruangan"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-muted small">
                                            Diproses Oleh
                                        </label>

                                        <div class="fw-semibold text-primary"
                                            id="det-approval"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-muted small">
                                            Waktu Mulai
                                        </label>

                                        <div class="fw-semibold"
                                            id="det-mulai"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-muted small">
                                            Waktu Selesai
                                        </label>

                                        <div class="fw-semibold"
                                            id="det-selesai"></div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- Keperluan -->
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body">

                                <label class="fw-bold mb-2">
                                    Keperluan
                                </label>

                                <div class="bg-light rounded-3 p-3 mb-4"
                                    id="det-keperluan"></div>

                                <label class="fw-bold mb-2">
                                    Catatan
                                </label>

                                <div class="bg-light rounded-3 p-3 text-muted mb-4"
                                    id="det-catatan"></div>

                                <!-- Alasan Penolakan -->
                                <div id="rejection-display-section" style="display:none;">
                                    <label class="fw-bold mb-2 text-danger">
                                        Alasan Penolakan
                                    </label>
                                    <div class="bg-danger-subtle text-danger rounded-3 p-3"
                                        id="det-keterangan"></div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="col-lg-4">

                        <!-- Foto -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body">

                                <label class="fw-bold mb-3">
                                    Foto Jaminan
                                </label>

                                <div class="bg-light rounded-4 overflow-hidden d-flex align-items-center justify-content-center"
                                    style="height: 260px;">

                                    <img id="det-jaminan"
                                        class="img-fluid w-100 h-100"
                                        style="object-fit: contain; display:none;">

                                    <div id="det-no-jaminan"
                                        class="text-center text-muted">

                                        <i class="fas fa-image fa-3x mb-3 opacity-50"></i>

                                        <div>
                                            Tidak ada foto jaminan
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- Barang -->
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body">

                                <label class="fw-bold mb-3">
                                    Daftar Barang
                                </label>

                                <div id="det-barang"
                                    class="pe-1"
                                    style="max-height: 220px; overflow-y: auto;">
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer border-0">
                <button type="button"
                    class="btn btn-light border px-4"
                    data-bs-dismiss="modal">

                    Tutup

                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Detail Modal Logic
    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', () => {
            const d = btn.dataset;

            [
                'kode',
                'ruangan',
                'jenis',
                'mulai',
                'selesai',
                'keperluan',
                'catatan',
                'approval'
            ].forEach(id => {
                const el = document.getElementById('det-' + id);
                if (el) el.textContent = d[id] || '-';
            });

            // STATUS
            const status = document.getElementById('det-status');
            status.textContent = d.status;
            status.className = 'badge rounded-pill px-3 py-2';

            const color = {
                selesai: 'success',
                ditolak: 'danger',
                rejected: 'danger',
                returned: 'success',
                dikembalikan: 'success'
            };

            const type = color[d.status.toLowerCase()] || 'secondary';
            status.classList.add(`bg-${type}-subtle`, `text-${type}`);

            // KETERANGAN
            const rejectDispSec = document.getElementById('rejection-display-section');
            const detKeterangan = document.getElementById('det-keterangan');

            if (d.status.toLowerCase() === 'ditolak' || d.status.toLowerCase() === 'rejected') {
                if (rejectDispSec) rejectDispSec.style.display = 'block';
                if (detKeterangan) detKeterangan.textContent = d.keterangan || 'Tidak ada alasan penolakan spesifik.';
            } else {
                if (rejectDispSec) rejectDispSec.style.display = 'none';
            }

            // BARANG
            document.getElementById('det-barang').innerHTML =
                d.barang && d.barang !== '-'
                ? d.barang.split(', ').map(item => `
                    <div class="mb-2">
                        <i class="fas fa-box text-primary me-2"></i>
                        ${item}
                    </div>
                `).join('')
                : '<div class="text-muted">Tidak ada daftar barang</div>';

            // JAMINAN
            const img = document.getElementById('det-jaminan');
            const empty = document.getElementById('det-no-jaminan');

            if (d.jaminan && d.jaminan !== '') {
                img.src = 'uploads/' + d.jaminan;
                img.style.display = 'block';
                empty.style.display = 'none';
            } else {
                img.style.display = 'none';
                empty.style.display = 'block';
            }
        });
    });


});
</script>