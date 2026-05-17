<?php
// View variables are provided by PageController
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <!-- Title -->
            <h1 class="mt-4">Persetujuan Peminjaman</h1>

            <!-- Action -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Kelola pengajuan peminjaman untuk ruangan Anda</span>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <?php if ($_GET['error'] === 'stok_kurang'): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>⚠️ Gagal Menyetujui!</strong>
                        Stok tidak mencukupi untuk <strong><?= htmlspecialchars($_GET['barang'] ?? 'barang yang dipinjam') ?></strong>
                        pada rentang waktu tersebut. Sudah ada peminjaman lain yang disetujui untuk waktu yang sama.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['error'] === 'not_found'): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Data tidak ditemukan.</strong> Peminjaman tidak dapat diproses.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['error'] === 'failed'): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal memperbarui status.</strong> Silakan coba lagi.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    ✅ Status peminjaman berhasil diperbarui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

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
                                            <option value="Pending">Pending</option>
                                            <option value="Disetujui">Disetujui</option>
                                            <option value="Pengembalian">Pengembalian</option>
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
                            <tbody id="peminjamanBody">
                                <?php
                                $no = 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $statusClass = 'bg-primary-subtle text-primary';
                                    if (strtolower($row['status']) == 'approved' || strtolower($row['status']) == 'disetujui') $statusClass = 'bg-success-subtle text-success';
                                    if (strtolower($row['status']) == 'rejected' || strtolower($row['status']) == 'ditolak') $statusClass = 'bg-danger-subtle text-danger';
                                    if (strtolower($row['status']) == 'returned' || strtolower($row['status']) == 'dikembalikan' || strtolower($row['status']) == 'selesai') $statusClass = 'bg-secondary-subtle text-secondary';
                                    if (strtolower($row['status']) == 'dipinjam') $statusClass = 'bg-info-subtle text-info';

                                    $tgl = date('dmY', strtotime($row['tanggal_dibuat']));
                                    $kode = "PJM-" . $tgl . "-" . $row['id_peminjaman'];
                                ?>
                                <tr data-status="<?= ucfirst(htmlspecialchars($row['status'])) ?>"
                                    data-type="<?= ucfirst(htmlspecialchars($row['jenis_peminjaman'])) ?>"
                                    data-date="<?= htmlspecialchars(date('Y-m-d', strtotime($row['tanggal_dibuat'] ?? 'now'))) ?>"
                                    data-room="<?= htmlspecialchars($row['nama_ruangan'] ?? '-') ?>"
                                    data-peminjam="<?= htmlspecialchars($row['peminjam'] ?? '-') ?>">
                                    <td class="text-center"><?= $no++ ?></td>
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
                                        <span class="badge <?= $statusClass ?>"><?= ucfirst(htmlspecialchars($row['status'])) ?></span>
                                    </td>
                                    <td class="text-center small">
                                        <?= date('d M Y - H:i', strtotime($row['waktu_mulai'])) ?>
                                    </td>
                                    <td class="text-center small">
                                        <?= date('d M Y - H:i', strtotime($row['waktu_selesai'])) ?>
                                    </td>
                                    <td class="text-center small">
                                        <?= htmlspecialchars(date('d M Y - H:i', strtotime($row['tanggal_dibuat']))) ?>
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
                                    <td colspan="9" class="text-center text-muted py-3">Tidak ada pengajuan peminjaman untuk ruangan Anda.</td>
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

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">

                <!-- Header -->
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Detail Peminjaman</h5>
                        <small class="text-muted">Konfirmasi dan tinjau pengajuan peminjaman</small>
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

                                    <!-- Alasan Penolakan (Input for Staff) -->
                                    <div id="rejection-input-section" style="display:none;">
                                        <label class="fw-bold mb-2 text-danger">Alasan Penolakan (Wajib jika menolak)</label>
                                        <textarea id="rejection-reason-input" class="form-control border-danger-subtle" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                                    </div>

                                    <!-- Alasan Penolakan (Display if already rejected) -->
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
                    <div id="action-buttons" class="d-flex gap-2 w-100 justify-content-end">
                        <!-- Dynamic Buttons -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</div>

<script>
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
            'dipinjam': 'info'
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
        if (d.jaminan) {
            img.src = 'uploads/' + d.jaminan;
            img.style.display = 'block';
            noImg.style.display = 'none';
        } else {
            img.style.display = 'none';
            noImg.style.display = 'block';
        }

        // Rejection Section Handling
        const rejectInputSec = document.getElementById('rejection-input-section');
        const rejectDispSec = document.getElementById('rejection-display-section');
        const detKeterangan = document.getElementById('det-keterangan');
        const rejectInput = document.getElementById('rejection-reason-input');

        rejectInputSec.style.display = 'none';
        rejectDispSec.style.display = 'none';
        rejectInput.value = '';

        if (d.status.toLowerCase() === 'pending') {
            rejectInputSec.style.display = 'block';
        } else if (d.status.toLowerCase() === 'ditolak' || d.status.toLowerCase() === 'rejected') {
            rejectDispSec.style.display = 'block';
            detKeterangan.textContent = d.keterangan || 'Tidak ada alasan penolakan spesifik.';
        }

        // Action Buttons
        const actionBox = document.getElementById('action-buttons');
        actionBox.innerHTML = '';
        
        if (d.status.toLowerCase() === 'pending') {
            actionBox.innerHTML = `
                <form id="form-reject" action="controllers/PeminjamanController.php?action=update_status" method="POST" class="m-0">
                    <input type="hidden" name="id_peminjaman" value="${d.id}">
                    <input type="hidden" name="status" value="Ditolak">
                    <input type="hidden" name="keterangan" id="hidden-reject-reason">
                    <button type="button" class="btn btn-outline-danger px-4" id="btn-submit-reject">Tolak</button>
                </form>
                <form action="controllers/PeminjamanController.php?action=update_status" method="POST" class="m-0">
                    <input type="hidden" name="id_peminjaman" value="${d.id}">
                    <input type="hidden" name="status" value="Disetujui">
                    <button type="submit" class="btn btn-primary px-4" onclick="confirmAction(event, 'Setujui pengajuan ini?')">Setujui Peminjaman</button>
                </form>
            `;

            // Handle Rejection Submit
            document.getElementById('btn-submit-reject').addEventListener('click', () => {
                const reason = rejectInput.value.trim();
                if (!reason) {
                    alert('Harap masukkan alasan penolakan!');
                    rejectInput.focus();
                    return;
                }
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Tolak pengajuan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger px-4 me-2 shadow-sm',
                        cancelButton: 'btn btn-secondary px-4 shadow-sm'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('hidden-reject-reason').value = reason;
                        document.getElementById('form-reject').submit();
                    }
                });
            });
        } else {
            actionBox.innerHTML = `<button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>`;
        }
    });
});
</script>
<?php
// PHP closing logic if any
?>