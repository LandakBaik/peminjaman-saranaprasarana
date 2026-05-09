<?php
// require_once '../../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();
$userModel = new \App\Models\User($db);
$stmt = $userModel->readAll();
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <!-- Title -->
            <h1 class="mt-4">Akun</h1>

            <!-- Action -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Daftar akun pengguna</span>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahStaffModal">
                    <i class="fas fa-plus me-1"></i> Tambah Akun
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
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $roleClass = 'bg-dark-subtle text-dark';
                                    if ($row['role'] == 'admin') $roleClass = 'bg-primary-subtle text-primary';
                                    if ($row['role'] == 'staff') $roleClass = 'bg-secondary-subtle text-secondary';
                                ?>
                                    <tr>
                                        <td class="text-center"><input type="checkbox"></td>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><strong><?= htmlspecialchars($row['nama'] ?? '') ?></strong></td>
                                        <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                                        <td class="text-center">
                                            <span class="badge <?= $roleClass ?>"><?= ucfirst(htmlspecialchars($row['role'])) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success">Aktif</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-info btn-sm">Detail</button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">1–3 of 3</small>

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

        <!-- Modal Tambah Akun Staff -->
        <div class="modal fade" id="tambahStaffModal" tabindex="-1" aria-labelledby="tambahStaffModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0 px-5 pb-4">
                        <div class="text-center mb-4">
                            <h2 class="text-primary fw-bold" id="tambahStaffModalLabel">Akun Staff</h2>
                            <div class="d-flex align-items-center justify-content-center mt-3">
                                <hr class="w-25">
                                <span class="text-muted mx-3">Data Staff</span>
                                <hr class="w-25">
                            </div>
                        </div>

                        <form action="controllers/UserController.php?action=create_staff" method="POST">
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold">Nama Staff</label>
                                    <input type="text" class="form-control" name="nama" placeholder="Roland" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="rolan@polije.ac.id" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold">Penanggung Jawab Ruangan</label>
                                    <select class="form-select text-muted" id="select_tipe_ruangan">
                                        <option value="" selected disabled>Pilih Tipe Ruangan...</option>
                                        <option value="non-laboratorium">Kelas</option>
                                        <option value="laboratorium">Laboratorium</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="XXXXXX" required>
                                </div>
                            </div>

                            <div class="row mt-3" id="ruangan_container" style="display: none;">
                                <div class="col-12">
                                    <label class="form-label text-muted fw-semibold">Pilih Ruangan yang Dikelola</label>
                                    <div class="border rounded p-3 bg-light" id="ruangan_list" style="max-height: 200px; overflow-y: auto;">
                                        <!-- Checkboxes will be populated here -->
                                    </div>
                                    <small class="text-muted mt-1 d-block">Hapus centang pada ruangan yang tidak dikelola oleh staff ini.</small>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-end align-items-center mt-4 pt-3">
                                <button type="button" class="btn btn-outline-secondary px-4 me-2" data-bs-dismiss="modal">Kembali</button>
                                <button type="submit" class="btn btn-primary px-4">Kirim</button>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const selectTipe = document.getElementById('select_tipe_ruangan');
    const ruanganContainer = document.getElementById('ruangan_container');
    const ruanganList = document.getElementById('ruangan_list');

    selectTipe.addEventListener('change', function() {
        const tipe = this.value;
        if (tipe) {
            fetch(`controllers/RuanganController.php?action=get_by_tipe&tipe=${tipe}`)
                .then(response => response.json())
                .then(data => {
                    ruanganList.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(ruangan => {
                            const div = document.createElement('div');
                            div.className = 'form-check mb-2';
                            div.innerHTML = `
                                <input class="form-check-input border-secondary" type="checkbox" name="ruangan_ids[]" value="${ruangan.id_ruangan}" id="ruang_${ruangan.id_ruangan}" checked>
                                <label class="form-check-label" for="ruang_${ruangan.id_ruangan}">
                                    ${ruangan.nama_ruangan} <small class="text-muted">(Kapasitas: ${ruangan.kapasitas})</small>
                                </label>
                            `;
                            ruanganList.appendChild(div);
                        });
                        ruanganContainer.style.display = 'block';
                    } else {
                        ruanganList.innerHTML = '<span class="text-muted">Tidak ada ruangan ditemukan untuk tipe ini.</span>';
                        ruanganContainer.style.display = 'block';
                    }
                })
                .catch(error => console.error('Error fetching ruangan:', error));
        } else {
            ruanganContainer.style.display = 'none';
        }
    });
});
</script>