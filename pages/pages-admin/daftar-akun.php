<?php
// require_once '../../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();

$userModel = new \App\Models\User($db);
$stmt = $userModel->readByRoles(['user']);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4">Akun</h1>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">
                    Daftar akun pengguna
                </span>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-white border border-primary text-primary btn-sm"
                        onclick="checkExportUser()">
                        <i class="fas fa-download me-1"></i>
                        Export
                    </button>

                    <a href="index.php?page=akun-staff" class="btn btn-primary btn-sm">
                        <i class="fa-regular fa-user"></i>
                        Akun Staff
                    </a>
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

                                    <h5 class="modal-title fw-bold">
                                        Filter Akun
                                    </h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <!-- Filter Role -->
                                    <div class="mb-3">

                                        <label class="form-label fw-semibold">
                                            Role
                                        </label>

                                        <select class="form-select" id="filterRole">

                                            <option value="">
                                                Semua
                                            </option>

                                            <option value="admin">
                                                Admin
                                            </option>

                                            <option value="staff">
                                                Staff
                                            </option>

                                            <option value="user">
                                                User
                                            </option>

                                        </select>

                                    </div>

                                    <!-- Filter Status -->
                                    <div class="mb-3">

                                        <label class="form-label fw-semibold">
                                            Status
                                        </label>

                                        <select class="form-select" id="filterStatusUser">

                                            <option value="">
                                                Semua
                                            </option>

                                            <option value="aktif">
                                                Aktif
                                            </option>

                                        </select>

                                    </div>

                                </div>

                                <div class="modal-footer border-0">

                                    <button type="button" class="btn btn-outline-secondary" id="resetUserFilter">

                                        Reset
                                    </button>

                                    <button type="button" class="btn btn-primary" id="applyUserFilter"
                                        data-bs-dismiss="modal">

                                        Terapkan
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Table -->
                    <div class="table-responsive">

                        <table class="table table-bordered align-middle" id="userTable">

                            <thead class="table-light">

                                <tr class="text-center">

                                    <th>
                                        <input type="checkbox" id="selectAllUser">
                                    </th>

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
                                    ?>

                                    <tr data-role="<?= strtolower($row['role']) ?>" data-status="aktif">

                                        <td class="text-center">
                                            <input type="checkbox" class="row-checkbox export-checkbox"
                                                value="<?= $row['id_pengguna'] ?>">
                                        </td>

                                        <td class="text-center">
                                            <?= $no++ ?>
                                        </td>

                                        <td>
                                            <strong>
                                                <?= htmlspecialchars($row['nama'] ?? '') ?>
                                            </strong>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['email'] ?? '') ?>
                                        </td>

                                        <td class="text-center">

                                            <span class="badge <?= $roleClass ?>">
                                                <?= ucfirst(htmlspecialchars($row['role'])) ?>
                                            </span>

                                        </td>

                                        <td class="text-center">

                                            <span class="badge bg-success-subtle text-success">
                                                Aktif
                                            </span>

                                        </td>

                                        <td class="text-center">

                                            <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#detailProfilModal<?= $row['id_pengguna'] ?>">
                                                Detail
                                            </button>

                                            <!-- Modal Detail Profil -->
                                            <div class="modal fade text-start" id="detailProfilModal<?= $row['id_pengguna'] ?>" tabindex="-1" aria-labelledby="detailProfilModalLabel<?= $row['id_pengguna'] ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content rounded-4 border-0 shadow">
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold" id="detailProfilModalLabel<?= $row['id_pengguna'] ?>">Detail Profil</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="d-flex align-items-center gap-3 mb-4">
                                                                <img src="<?= !empty($row['foto_profil']) ? 'uploads/' . htmlspecialchars($row['foto_profil']) : 'assets/img/no-image.png' ?>" 
                                                                     class="rounded-3" width="80" height="80" style="object-fit:cover;">
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

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-2">

                        <small class="text-muted" id="rowCountUser">

                            0 of 0

                        </small>

                        <div class="d-flex align-items-center">

                            <small class="me-2">
                                Rows per page: 10
                            </small>

                            <button class="btn btn-light btn-sm me-1">
                                &lt;
                            </button>

                            <span>1</span>

                            <button class="btn btn-light btn-sm ms-1">
                                &gt;
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>
    <script>
        function checkExportUser() {

            let checked = document.querySelectorAll(
                '.export-checkbox:checked'
            );

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
            const selectTipe = document.getElementById('select_tipe_ruangan');
            const ruanganContainer = document.getElementById('ruangan_container');
            const ruanganList = document.getElementById('ruangan_list');

            selectTipe.addEventListener('change', function () {
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
    <footer>
        <?php include 'footer.php'; ?>
    </footer>

</div>