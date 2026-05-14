<?php
$database = new \App\Config\Database();
$db = $database->getConnection();

$peminjaman = new \App\Models\Peminjaman($db);

// TRUE = history
$stmt = $peminjaman->readAll(true);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4 fw-bold">Semua Riwayat Peminjaman</h1>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">
                    Seluruh riwayat peminjaman sistem
                </span>
                <button type="button" class="btn btn-white border border-primary text-primary btn-sm"
                    onclick="checkExportRiwayat()">
                    <i class="fas fa-download me-1"></i>
                    Export
                </button>
            </div>

            <div class="card mb-4">
                <div class="card-body">

                    <!-- Search + Filter -->
                    <div class="d-flex align-items-center mb-3">
                        <!-- Tombol Filter -->
                        <button type="button"
                            class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center me-3"
                            style="width: 42px; height: 42px;" data-bs-toggle="modal" data-bs-target="#filterRiwayatModal"
                            id="btnFilterToggle" title="Filter">
                            <i class="fas fa-filter" style="font-size: 16px;"></i>
                        </button>

                        <!-- Search Bar -->
                        <input type="text" class="form-control" style="max-width: 250px; height: 42px;"
                            placeholder="Search..." id="searchRiwayat">
                    </div>

                    <!-- popup filter -->
                    <div class="modal fade" id="filterRiwayatModal" tabindex="-1" aria-labelledby="filterModalLabel"
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
                                        <select class="form-select" id="filterStatusRiwayat">
                                            <option value="" selected>Semua</option>
                                            <option value="Disetujui">Disetujui</option>
                                            <option value="Ditolak">Ditolak</option>
                                            <option value="Selesai">Selesai</option>
                                            <option value="Dibatalkan">Dibatalkan</option>
                                        </select>
                                    </div>
                                    <!-- Jenis -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jenis</label>
                                        <select class="form-select" id="filterJenisRiwayat">
                                            <option value="" selected>Semua</option>
                                            <option value="Barang">Barang</option>
                                            <option value="Ruangan">Ruangan</option>
                                        </select>
                                    </div>
                                    <!-- Tanggal -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tanggal</label>
                                        <input type="date" class="form-control" id="filterTanggalRiwayat">
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary"
                                        id="btnResetRiwayatFilter">Reset</button>
                                    <button type="button" class="btn btn-primary" id="btnApplyRiwayatFilter"
                                        data-bs-dismiss="modal">Terapkan</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead class="table-light">
                                <tr class="text-center">
                                    <th><input type="checkbox" id="selectAllRiwayat"></th>
                                    <th>No</th>
                                    <th>Peminjam</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Tanggal</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="riwayatBody">

                                <?php
                                $no = 1;

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                    $statusClass = 'bg-secondary-subtle text-secondary';
                                    if (strtolower($row['status']) == 'disetujui' || strtolower($row['status']) == 'approved') $statusClass = 'bg-success-subtle text-success';
                                    if (strtolower($row['status']) == 'ditolak' || strtolower($row['status']) == 'rejected') $statusClass = 'bg-danger-subtle text-danger';
                                    if (strtolower($row['status']) == 'dibatalkan' || strtolower($row['status']) == 'cancelled') $statusClass = 'bg-warning-subtle text-warning';
                                    if (strtolower($row['status']) == 'dipinjam') $statusClass = 'bg-info-subtle text-info';
                                    if (strtolower($row['status']) == 'selesai' || strtolower($row['status']) == 'returned') $statusClass = 'bg-secondary-subtle text-secondary';
                                    
                                    $tgl = date('dmY', strtotime($row['tanggal_dibuat']));
                                    $kode = "PJM-" . $tgl . "-" . $row['id_peminjaman'];
                                    ?>

                                    <tr data-status="<?= ucfirst(htmlspecialchars($row['status'])) ?>"
                                        data-type="<?= ucfirst(htmlspecialchars($row['jenis_peminjaman'])) ?>"
                                        data-date="<?= htmlspecialchars(date('Y-m-d', strtotime($row['tanggal_dibuat']))) ?>">

                                        <td class="text-center">
                                            <input type="checkbox" class="export-checkbox row-checkbox" value="<?= $row['id_peminjaman'] ?>">
                                        </td>

                                        <td class="text-center">
                                            <?= $no++ ?>
                                        </td>

                                        <td>
                                            <strong><?= htmlspecialchars($row['peminjam']) ?></strong><br>
                                            <small class="text-muted">#<?= $row['id_peminjaman'] ?></small>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge border text-dark bg-light">
                                                <?= ucfirst($row['jenis_peminjaman']) ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge <?= $statusClass ?>">
                                                <?= ucfirst(htmlspecialchars($row['status'])) ?>
                                            </span>
                                        </td>

                                        <td class="text-center small">
                                            <?= date('d M Y - H:i', strtotime($row['waktu_mulai'])) ?>
                                        </td>

                                        <td class="text-center small">
                                            <?= date('d M Y - H:i', strtotime($row['waktu_selesai'])) ?>
                                        </td>

                                        <td class="text-center small">
                                            <?= date('d M Y - H:i', strtotime($row['tanggal_dibuat'])) ?>
                                        </td>

                                        <td class="text-center">
                                            <?= htmlspecialchars($row['staff_approval'] ?? '-') ?>
                                        </td>

                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm btn-detail" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailRiwayatModal" 
                                                data-id="<?= $row['id_peminjaman'] ?>"
                                                data-kode="<?= htmlspecialchars($kode) ?>"
                                                data-peminjam="<?= htmlspecialchars($row['peminjam'] ?? '-') ?>"
                                                data-ruangan="<?= htmlspecialchars($row['nama_ruangan'] ?? '-') ?>"
                                                data-jenis="<?= htmlspecialchars(ucfirst($row['jenis_peminjaman'])) ?>"
                                                data-mulai="<?= htmlspecialchars(date('d M Y - H:i', strtotime($row['waktu_mulai']))) ?>"
                                                data-selesai="<?= htmlspecialchars(date('d M Y - H:i', strtotime($row['waktu_selesai']))) ?>"
                                                data-keperluan="<?= htmlspecialchars($row['keperluan']) ?>"
                                                data-catatan="<?= htmlspecialchars($row['catatan'] ?? '-') ?>"
                                                data-jaminan="<?= htmlspecialchars($row['jaminan'] ?? '') ?>"
                                                data-barang="<?= htmlspecialchars($row['nama_barang'] ?? '-') ?>"
                                                data-status="<?= htmlspecialchars(ucfirst($row['status'])) ?>"
                                                data-keterangan="<?= htmlspecialchars($row['keterangan'] ?? '') ?>">
                                                Detail
                                            </button>
                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted" id="rowCountRiwayat">Menampilkan 0 data</small>

                        <div class="d-flex align-items-center">
                            <small class="me-2">Rows per page: 
                                <select id="rowsPerPageRiwayat" class="form-select form-select-sm d-inline-block w-auto border-0 bg-transparent py-0" style="cursor: pointer; box-shadow: none;">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                </select>
                            </small>
                            <button class="btn btn-light btn-sm me-1" id="btnPrevPageRiwayat">&lt;</button>
                            <span id="currentPageNumRiwayat" class="mx-2">1</span>
                            <button class="btn btn-light btn-sm ms-1" id="btnNextPageRiwayat">&gt;</button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailRiwayatModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Detail Peminjaman</h5>
                        <small class="text-muted">Informasi lengkap riwayat peminjaman</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-4">
                                        <div>
                                            <h6 class="fw-bold text-primary mb-1">Informasi Peminjaman</h6>
                                            <small class="text-muted" id="det-kode"></small>
                                        </div>
                                        <span class="badge rounded-pill px-3 py-2" id="det-status"></span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="text-muted small d-block">Peminjam</label>
                                            <div class="fw-semibold" id="det-peminjam"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small d-block">Jenis</label>
                                            <div class="fw-semibold" id="det-jenis"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small d-block">Ruangan</label>
                                            <div class="fw-semibold" id="det-ruangan"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <label class="text-muted small d-block">Waktu Mulai</label>
                                                <div class="fw-semibold" id="det-mulai"></div>
                                            </div>
                                            <div>
                                                <label class="text-muted small d-block">Waktu Selesai</label>
                                                <div class="fw-semibold" id="det-selesai"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-body">
                                    <label class="fw-bold mb-2">Keperluan</label>
                                    <div class="bg-light rounded-3 p-3 mb-4" id="det-keperluan"></div>
                                    <label class="fw-bold mb-2">Catatan Peminjam</label>
                                    <div class="bg-light rounded-3 p-3 text-muted mb-4" id="det-catatan"></div>
                                    <div id="rejection-display-section" style="display:none;">
                                        <label class="fw-bold mb-2 text-danger">Alasan Penolakan</label>
                                        <div class="bg-danger-subtle text-danger rounded-3 p-3" id="det-keterangan"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-body">
                                    <label class="fw-bold mb-3">Daftar Barang / Aset</label>
                                    <div id="det-barang" class="pe-1" style="max-height: 200px; overflow-y: auto;"></div>
                                </div>
                            </div>
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-body text-center">
                                    <label class="fw-bold mb-3 d-block text-start">Foto Jaminan</label>
                                    <div class="bg-light rounded-4 overflow-hidden d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <img id="det-jaminan" class="img-fluid w-100 h-100" style="object-fit: contain; display:none;">
                                        <div id="det-no-jaminan" class="text-center text-muted py-5">
                                            <i class="fas fa-image fa-2x mb-2 opacity-50"></i>
                                            <div class="small">Tidak ada foto jaminan</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkExportRiwayat() {
            let checked = document.querySelectorAll('.export-checkbox:checked');
            if (checked.length === 0) {
                alert('Pilih minimal satu data untuk diexport.');
                return;
            }
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'controllers/PeminjamanController.php?action=export';
            checked.forEach(function (checkbox) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id_peminjaman[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });
            document.body.appendChild(form);
            form.submit();
            setTimeout(() => document.body.removeChild(form), 1000);
        }

        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                const d = btn.dataset;
                document.getElementById('det-kode').textContent = d.kode;
                document.getElementById('det-peminjam').textContent = d.peminjam;
                document.getElementById('det-jenis').textContent = d.jenis;
                document.getElementById('det-ruangan').textContent = d.ruangan;
                document.getElementById('det-mulai').textContent = d.mulai;
                document.getElementById('det-selesai').textContent = d.selesai;
                document.getElementById('det-keperluan').textContent = d.keperluan;
                document.getElementById('det-catatan').textContent = d.catatan || '-';

                const status = document.getElementById('det-status');
                status.textContent = d.status;
                status.className = 'badge rounded-pill px-3 py-2';
                
                const colors = {
                    'disetujui': 'success',
                    'ditolak': 'danger',
                    'selesai': 'secondary',
                    'dibatalkan': 'warning',
                    'dipinjam': 'info'
                };
                const type = colors[d.status.toLowerCase()] || 'secondary';
                status.classList.add(`bg-${type}-subtle`, `text-${type}`);

                const barangContainer = document.getElementById('det-barang');
                if (d.barang && d.barang !== '-') {
                    barangContainer.innerHTML = d.barang.split(', ').map(item => `
                        <div class="d-flex align-items-center mb-2 p-2 bg-light rounded">
                            <i class="fas fa-box text-primary me-2"></i>
                            <span class="small">${item}</span>
                        </div>
                    `).join('');
                } else {
                    barangContainer.innerHTML = '<div class="text-muted small">Tidak ada daftar barang</div>';
                }

                const img = document.getElementById('det-jaminan');
                const noImg = document.getElementById('det-no-jaminan');
                if (d.jaminan && d.jaminan !== '') {
                    img.src = 'uploads/' + d.jaminan;
                    img.style.display = 'block';
                    noImg.style.display = 'none';
                } else {
                    img.style.display = 'none';
                    noImg.style.display = 'block';
                }

                const rejectDispSec = document.getElementById('rejection-display-section');
                const detKeterangan = document.getElementById('det-keterangan');
                if (d.status.toLowerCase() === 'ditolak') {
                    rejectDispSec.style.display = 'block';
                    detKeterangan.textContent = d.keterangan || 'Tidak ada alasan penolakan spesifik.';
                } else {
                    rejectDispSec.style.display = 'none';
                }
            });
        });
    </script>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</div>