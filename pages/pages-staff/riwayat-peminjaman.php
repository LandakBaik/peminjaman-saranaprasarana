<?php
$database = new \App\Config\Database();
$db = $database->getConnection();

$peminjaman = new \App\Models\Peminjaman($db);

$userId = $_SESSION['user']['id'] ?? 0;

// TRUE = history
$stmt = $peminjaman->readByStaff($userId, true);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4 fw-bold">Riwayat Peminjaman</h1>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">
                    Riwayat peminjaman pada ruangan/barang yang Anda kelola
                </span>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <!-- Search + Filter -->
                    <div class="d-flex mb-3">
                        <button class="btn btn-light border me-2">
                            <i class="fas fa-filter"></i>
                        </button>
                        <input type="text" class="form-control w-25" placeholder="Search...">
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Peminjam</th>
                                    <th>Tipe</th>
                                    <th>Ruangan</th>
                                    <th>Status</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $no = 1;

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                    $statusClass = 'bg-secondary-subtle text-secondary';
                                    if (strtolower($row['status']) == 'approved' || strtolower($row['status']) == 'disetujui') $statusClass = 'bg-success-subtle text-success';
                                    if (strtolower($row['status']) == 'rejected' || strtolower($row['status']) == 'ditolak') $statusClass = 'bg-danger-subtle text-danger';
                                    if (strtolower($row['status']) == 'returned' || strtolower($row['status']) == 'dikembalikan' || strtolower($row['status']) == 'selesai') $statusClass = 'bg-secondary-subtle text-secondary';
                                    if (strtolower($row['status']) == 'dipinjam') $statusClass = 'bg-info-subtle text-info';

                                    $tgl = date('dmY', strtotime($row['tanggal_dibuat']));
                                    $kode = "PJM-" . $tgl . "-" . $row['id_peminjaman'];
                                    ?>

                                    <tr>

                                        <td class="text-center">
                                            <?= $no++ ?>
                                        </td>

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

                                        <td class="text-center">
                                            <span class="badge <?= $statusClass ?>">
                                                <?= htmlspecialchars($row['status']) ?>
                                            </span>
                                        </td>

                                        <td class="text-center small">
                                            <?= date('d M, H:i', strtotime($row['waktu_mulai'])) ?>
                                        </td>

                                        <td class="text-center small">
                                            <?= date('d M, H:i', strtotime($row['waktu_selesai'])) ?>
                                        </td>

                                        <td class="text-center small">
                                            <?= htmlspecialchars(date('d M Y', strtotime($row['tanggal_dibuat']))) ?>
                                        </td>

                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm btn-detail" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal" 
                                                data-id="<?= $row['id_peminjaman'] ?>"
                                                data-kode="<?= htmlspecialchars($kode) ?>"
                                                data-peminjam="<?= htmlspecialchars($row['peminjam'] ?? '-') ?>"
                                                data-ruangan="<?= htmlspecialchars($row['nama_ruangan'] ?? '-') ?>"
                                                data-jenis="<?= htmlspecialchars(ucfirst($row['jenis_peminjaman'])) ?>"
                                                data-mulai="<?= htmlspecialchars($row['waktu_mulai']) ?>"
                                                data-selesai="<?= htmlspecialchars($row['waktu_selesai']) ?>"
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

                                <?php if ($stmt->rowCount() == 0): ?>

                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-3">
                                            Tidak ada riwayat peminjaman.
                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">

                <!-- Header -->
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Detail Peminjaman</h5>
                        <small class="text-muted">Riwayat dan informasi lengkap</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- LEFT -->
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
                                            <label class="text-muted small">Peminjam</label>
                                            <div class="fw-semibold" id="det-peminjam"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small">Jenis</label>
                                            <div class="fw-semibold" id="det-jenis"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small">Ruangan</label>
                                            <div class="fw-semibold" id="det-ruangan"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="text-muted small">Waktu Mulai</label>
                                                <div class="fw-semibold" id="det-mulai"></div>
                                            </div>
                                            <div>
                                                <label class="text-muted small">Waktu Selesai</label>
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

                                    <!-- Alasan Penolakan (Display only) -->
                                    <div id="rejection-display-section" style="display:none;">
                                        <label class="fw-bold mb-2 text-danger">Alasan Penolakan</label>
                                        <div class="bg-danger-subtle text-danger rounded-3 p-3" id="det-keterangan"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT -->
                        <div class="col-lg-4">
                            <!-- Barang -->
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-body">
                                    <label class="fw-bold mb-3">Daftar Barang / Aset</label>
                                    <div id="det-barang" class="pe-1" style="max-height: 200px; overflow-y: auto;"></div>
                                </div>
                            </div>

                            <!-- Jaminan -->
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

                <!-- Footer -->
                <div class="modal-footer border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', () => {
            const d = btn.dataset;

            // Basic Info
            document.getElementById('det-kode').textContent = d.kode;
            document.getElementById('det-peminjam').textContent = d.peminjam;
            document.getElementById('det-jenis').textContent = d.jenis;
            document.getElementById('det-ruangan').textContent = d.ruangan;
            document.getElementById('det-mulai').textContent = d.mulai;
            document.getElementById('det-selesai').textContent = d.selesai;
            document.getElementById('det-keperluan').textContent = d.keperluan;
            document.getElementById('det-catatan').textContent = d.catatan || '-';

            // Status Badge
            const status = document.getElementById('det-status');
            status.textContent = d.status;
            status.className = 'badge rounded-pill px-3 py-2';
            
            const colors = {
                'pending': 'primary',
                'disetujui': 'success',
                'approved': 'success',
                'ditolak': 'danger',
                'rejected': 'danger',
                'dipinjam': 'info',
                'selesai': 'secondary',
                'dikembalikan': 'secondary',
                'returned': 'secondary'
            };
            const type = colors[d.status.toLowerCase()] || 'secondary';
            status.classList.add(`bg-${type}-subtle`, `text-${type}`);

            // Items
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

            // Jaminan
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

            // Rejection Display Handling
            const rejectDispSec = document.getElementById('rejection-display-section');
            const detKeterangan = document.getElementById('det-keterangan');

            rejectDispSec.style.display = 'none';
            if (d.status.toLowerCase() === 'ditolak' || d.status.toLowerCase() === 'rejected') {
                rejectDispSec.style.display = 'block';
                detKeterangan.textContent = d.keterangan || 'Tidak ada alasan penolakan spesifik.';
            }
        });
    });
});
</script>