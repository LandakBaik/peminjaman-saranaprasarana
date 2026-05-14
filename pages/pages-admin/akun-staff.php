<?php
$database = new \App\Config\Database();
$db = $database->getConnection();

$userModel = new \App\Models\User($db);
$stmt = $userModel->readByRoles(['staff']);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4">Akun Staff</h1>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">
                    Daftar akun staff
                </span>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-white border border-primary text-primary btn-sm"
                        onclick="checkExportUser()">
                        <i class="fas fa-download me-1"></i>
                        Export
                    </button>

                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#tambahStaffModal">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Akun
                    </button>
                </div>
            </div>

            <!-- Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Search + Filter -->
                    <div class="d-flex mb-3">
                        <button class="btn btn-light border me-2" data-bs-toggle="modal"
                            data-bs-target="#filterUserModal">
                            <i class="fas fa-filter"></i>
                        </button>
                        <input type="text" class="form-control w-25" placeholder="Search..." id="searchUser">
                    </div>

                    <!-- Modal Filter -->
                    <div class="modal fade" id="filterUserModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-bold">Filter Akun</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Role</label>
                                        <select class="form-select" id="filterRole">
                                            <option value="">Semua</option>
                                            <option value="staff">Staff</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-outline-secondary" id="resetUserFilter">Reset</button>
                                    <button type="button" class="btn btn-primary" id="applyUserFilter" data-bs-dismiss="modal">Terapkan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="userTable">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th><input type="checkbox" id="selectAllUser"></th>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="userBody">
                                <?php
                                $no = 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $roleClass = 'bg-dark-subtle text-dark';
                                    if ($row['role'] == 'admin') {
                                        $roleClass = 'bg-primary-subtle text-primary';
                                    }
                                    if ($row['role'] == 'staff') {
                                        $roleClass = 'bg-secondary-subtle text-secondary';
                                    }

                                    $statusClass = $row['status'] == 'aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
                                    ?>
                                    <tr data-role="<?= strtolower($row['role']) ?>" data-status="<?= htmlspecialchars($row['status']) ?>">
                                        <td class="text-center">
                                            <input type="checkbox" class="row-checkbox export-checkbox" value="<?= $row['id_pengguna'] ?>">
                                        </td>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><strong><?= htmlspecialchars($row['nama'] ?? '') ?></strong></td>
                                        <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                                        <td class="text-center">
                                            <span class="badge <?= $roleClass ?>">
                                                <?= ucfirst(htmlspecialchars($row['role'] ?? '')) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <select class="form-select form-select-sm status-dropdown" 
                                                onchange="location.href='controllers/UserController.php?action=update_status&id=<?= $row['id_pengguna'] ?>&status=' + this.value"
                                                style="min-width: 100px; border-radius: 20px; font-size: 0.75rem; 
                                                <?= $row['status'] == 'aktif' ? 'background-color: #d1e7dd; color: #0f5132; border-color: #badbcc;' : 'background-color: #f8d7da; color: #842029; border-color: #f5c2c7;' ?>">
                                                <option value="aktif" <?= $row['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                                <option value="nonaktif" <?= $row['status'] == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#detailProfilModal<?= $row['id_pengguna'] ?>">
                                                    Detail
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#editAkunModal<?= $row['id_pengguna'] ?>">
                                                    Edit
                                                </button>
                                            </div>

                                            <!-- Modal Detail Profil -->
                                            <div class="modal fade text-start" id="detailProfilModal<?= $row['id_pengguna'] ?>" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content rounded-4 border-0 shadow">
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold">Detail Profil</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="d-flex align-items-center gap-3 mb-4">
                                                                <img src="<?= !empty($row['foto_profil']) ? 'uploads/' . htmlspecialchars($row['foto_profil']) : 'assets/img/no-image.png' ?>" class="rounded-3" width="80" height="80" style="object-fit:cover;">
                                                                <div>
                                                                    <h4 class="fw-semibold mb-1"><?= htmlspecialchars($row['nama_panggilan'] ?? $row['nama'] ?? 'User') ?></h4>
                                                                    <span class="mb-0 mt-1 small"><?= ucfirst(htmlspecialchars($row['role'])) ?></span>
                                                                    <p class="text-secondary mb-0 mt-1 small"><?= htmlspecialchars($row['email']) ?></p>
                                                                </div>
                                                            </div>
                                                            <hr class="my-4">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <label class="form-label text-muted small mb-1">Nama Lengkap</label>
                                                                    <div class="form-control bg-light"><?= htmlspecialchars($row['nama'] ?? '-') ?></div>
                                                                </div>
                                                                <div class="col-12 col-md-6">
                                                                    <label class="form-label text-muted small mb-1">Nomor Telepon</label>
                                                                    <div class="form-control bg-light"><?= htmlspecialchars($row['nomor_telepon'] ?? '-') ?></div>
                                                                </div>
                                                                <div class="col-12 col-md-6">
                                                                    <label class="form-label text-muted small mb-1">Tanggal Lahir</label>
                                                                    <div class="form-control bg-light"><?= !empty($row['tanggal_lahir']) ? date('d/m/Y', strtotime($row['tanggal_lahir'])) : '-' ?></div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label text-muted small mb-1">Jenis Kelamin</label>
                                                                    <div class="form-control bg-light"><?= htmlspecialchars($row['jenis_kelamin'] ?? '-') ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Edit Akun -->
                                            <div class="modal fade text-start" id="editAkunModal<?= $row['id_pengguna'] ?>" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content rounded-4 border-0 shadow">
                                                        <form action="controllers/UserController.php?action=update" method="POST">
                                                            <input type="hidden" name="id_pengguna" value="<?= $row['id_pengguna'] ?>">
                                                            <div class="modal-header border-0 pb-0">
                                                                <h5 class="modal-title fw-bold">Edit Akun</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body p-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">Nama</label>
                                                                    <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">Email</label>
                                                                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">Status Akun</label>
                                                                    <select class="form-select" name="status">
                                                                        <option value="aktif" <?= $row['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                                                        <option value="nonaktif" <?= $row['status'] == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                                                                    </select>
                                                                </div>
                                                                <input type="hidden" name="role" value="staff">

                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">Penanggung Jawab Ruangan</label>
                                                                    <select class="form-select text-muted select_tipe_ruangan_edit" data-user-id="<?= $row['id_pengguna'] ?>" id="select_tipe_ruangan_edit_<?= $row['id_pengguna'] ?>">
                                                                        <option value="" selected disabled>Pilih Tipe Ruangan...</option>
                                                                        <option value="non-laboratorium">Kelas</option>
                                                                        <option value="laboratorium">Laboratorium</option>
                                                                    </select>
                                                                </div>

                                                                <div id="ruangan_container_edit_<?= $row['id_pengguna'] ?>" style="display: none;">
                                                                    <label class="form-label fw-semibold">Pilih Ruangan yang Dikelola</label>
                                                                    <div class="border rounded p-3 bg-light ruangan_list_edit" id="ruangan_list_edit_<?= $row['id_pengguna'] ?>" style="max-height: 200px; overflow-y: auto;">
                                                                        <!-- Loaded via AJAX -->
                                                                    </div>
                                                                    <small class="text-muted mt-1 d-block">Hapus centang pada ruangan yang tidak dikelola oleh staff ini.</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-0">
                                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Table -->
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted" id="rowCountUser">0 of 0</small>
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
    </main>

    <!-- Modal Tambah Akun Staff -->
    <div class="modal fade" id="tambahStaffModal" tabindex="-1" aria-labelledby="tambahStaffModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header border-0 pb-0">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>

                <div class="modal-body pt-0 px-5 pb-4">

                    <div class="text-center mb-4">

                        <h2 class="text-primary fw-bold" id="tambahStaffModalLabel">

                            Akun Staff
                        </h2>

                        <div class="d-flex align-items-center justify-content-center mt-3">

                            <hr class="w-25">

                            <span class="text-muted mx-3">
                                Data Staff
                            </span>

                            <hr class="w-25">

                        </div>

                    </div>

                    <form action="controllers/UserController.php?action=create_staff" method="POST">

                        <div class="row g-4 mb-4">

                            <div class="col-md-6">

                                <label class="form-label text-muted fw-semibold">
                                    Nama Staff
                                </label>

                                <input type="text" class="form-control" name="nama" placeholder="Roland" required>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label text-muted fw-semibold">
                                    Email
                                </label>

                                <input type="email" class="form-control" name="email"
                                    placeholder="rolan@polije.ac.id" required>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label text-muted fw-semibold">
                                    Penanggung Jawab Ruangan
                                </label>

                                <select class="form-select text-muted" id="select_tipe_ruangan">
                                    <option value="" selected disabled>Pilih Tipe Ruangan...</option>
                                    <option value="non-laboratorium">Kelas</option>
                                    <option value="laboratorium">Laboratorium</option>
                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label text-muted fw-semibold">
                                    Password
                                </label>

                                <input type="password" class="form-control" name="password" id="staff_password" placeholder="XXXXXX"
                                    minlength="6" pattern="[a-zA-Z0-9]+" required>
                                <div class="invalid-feedback">Password harus minimal 6 karakter dan hanya berisi huruf/angka!</div>

                            </div>

                        </div>

                        <div class="row mt-3" id="ruangan_container" style="display: none;">
                            <div class="col-12">
                                <label class="form-label text-muted fw-semibold">Pilih Ruangan yang Dikelola</label>
                                <div class="border rounded p-3 bg-light" id="ruangan_list"
                                    style="max-height: 200px; overflow-y: auto;">

                                </div>
                                <small class="text-muted mt-1 d-block">Hapus centang pada ruangan yang tidak
                                    dikelola oleh staff ini.</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end align-items-center mt-4 pt-3">

                            <button type="button" class="btn btn-outline-secondary px-4 me-2"
                                data-bs-dismiss="modal">

                                Kembali
                            </button>

                            <button type="submit" class="btn btn-primary px-4">

                                Kirim
                            </button>

                        </div>

                    </form>


                </div>

            </div>

        </div>

    </div>

    <script>
        function checkExportUser() {
            let checked = document.querySelectorAll('.export-checkbox:checked');
            if (checked.length === 0) {
                alert('Anda perlu memilih minimal 1 akun untuk membuat laporan.');
                return;
            }
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'controllers/UserController.php?action=export';
            checked.forEach(function (checkbox) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id_user[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });
            document.body.appendChild(form);
            form.submit();
            setTimeout(() => document.body.removeChild(form), 1000);
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Handle Add Modal
            const selectTipeAdd = document.getElementById('select_tipe_ruangan');
            const ruanganContainerAdd = document.getElementById('ruangan_container');
            const ruanganListAdd = document.getElementById('ruangan_list');

            if (selectTipeAdd) {
                selectTipeAdd.addEventListener('change', function () {
                    const tipe = this.value;
                    if (tipe) {
                        fetch(`controllers/RuanganController.php?action=get_by_tipe&tipe=${tipe}`)
                            .then(response => response.json())
                            .then(data => {
                                ruanganListAdd.innerHTML = '';
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
                                        ruanganListAdd.appendChild(div);
                                    });
                                    ruanganContainerAdd.style.display = 'block';
                                } else {
                                    ruanganListAdd.innerHTML = '<span class="text-muted">Tidak ada ruangan ditemukan untuk tipe ini.</span>';
                                    ruanganContainerAdd.style.display = 'block';
                                }
                            })
                            .catch(error => console.error('Error fetching ruangan:', error));
                    } else {
                        ruanganContainerAdd.style.display = 'none';
                    }
                });
            }

            // Handle Edit Modals
            const editTipeSelects = document.querySelectorAll('.select_tipe_ruangan_edit');
            editTipeSelects.forEach(select => {
                select.addEventListener('change', function () {
                    const userId = this.getAttribute('data-user-id');
                    const tipe = this.value;
                    const container = document.getElementById(`ruangan_container_edit_${userId}`);
                    const list = document.getElementById(`ruangan_list_edit_${userId}`);

                    if (tipe) {
                        fetch(`controllers/RuanganController.php?action=get_by_tipe&tipe=${tipe}`)
                            .then(response => response.json())
                            .then(data => {
                                list.innerHTML = '';
                                if (data.length > 0) {
                                    data.forEach(ruangan => {
                                        const isChecked = (ruangan.id_pengguna == userId) ? 'checked' : '';
                                        const div = document.createElement('div');
                                        div.className = 'form-check mb-2';
                                        div.innerHTML = `
                                            <input class="form-check-input border-secondary" type="checkbox" name="ruangan_ids[]" value="${ruangan.id_ruangan}" id="ruang_edit_${userId}_${ruangan.id_ruangan}" ${isChecked}>
                                            <label class="form-check-label" for="ruang_edit_${userId}_${ruangan.id_ruangan}">
                                                ${ruangan.nama_ruangan} <small class="text-muted">(Kapasitas: ${ruangan.kapasitas})</small>
                                            </label>
                                        `;
                                        list.appendChild(div);
                                    });
                                    container.style.display = 'block';
                                } else {
                                    list.innerHTML = '<span class="text-muted">Tidak ada ruangan ditemukan untuk tipe ini.</span>';
                                    container.style.display = 'block';
                                }
                            })
                            .catch(error => console.error('Error fetching ruangan:', error));
                    } else {
                        container.style.display = 'none';
                    }
                });
            });
        });

        // Password validation for Add Staff form
        document.querySelector('#tambahStaffModal form').addEventListener('submit', function(e) {
            const password = document.getElementById('staff_password').value;
            const alnumRegex = /^[a-zA-Z0-9]+$/;
            
            if (password.length < 6 || !alnumRegex.test(password)) {
                e.preventDefault();
                document.getElementById('staff_password').classList.add('is-invalid');
            } else {
                document.getElementById('staff_password').classList.remove('is-invalid');
            }
        });

        document.getElementById('staff_password').addEventListener('input', function() {
            const alnumRegex = /^[a-zA-Z0-9]+$/;
            if (this.value.length >= 6 && alnumRegex.test(this.value)) {
                this.classList.remove('is-invalid');
            }
        });
    </script>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</div>
