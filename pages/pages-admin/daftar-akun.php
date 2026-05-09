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

            <h1 class="mt-4">Akun</h1>

            <div class="d-flex justify-content-between align-items-center mb-3">

                <span class="text-muted">
                    Daftar akun pengguna
                </span>

                <button type="button"
                    class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#tambahStaffModal">

                    <i class="fas fa-plus me-1"></i>
                    Tambah Akun Staff
                </button>

            </div>

            <!-- Card -->
            <div class="card mb-4">

                <div class="card-body">

                    <!-- Search + Filter -->
                    <div class="d-flex mb-3">

                        <button class="btn btn-light border me-2"
                            data-bs-toggle="modal"
                            data-bs-target="#filterUserModal">

                            <i class="fas fa-filter"></i>
                        </button>

                        <input type="text"
                            class="form-control w-25"
                            placeholder="Search..."
                            id="searchUser">

                    </div>

                    <!-- Modal Filter -->
                    <div class="modal fade"
                        id="filterUserModal"
                        tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content rounded-4 border-0 shadow">

                                <div class="modal-header border-0">

                                    <h5 class="modal-title fw-bold">
                                        Filter Akun
                                    </h5>

                                    <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <!-- Filter Role -->
                                    <div class="mb-3">

                                        <label class="form-label fw-semibold">
                                            Role
                                        </label>

                                        <select class="form-select"
                                            id="filterRole">

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

                                        <select class="form-select"
                                            id="filterStatusUser">

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

                                    <button type="button"
                                        class="btn btn-outline-secondary"
                                        id="resetUserFilter">

                                        Reset
                                    </button>

                                    <button type="button"
                                        class="btn btn-primary"
                                        id="applyUserFilter"
                                        data-bs-dismiss="modal">

                                        Terapkan
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Table -->
                    <div class="table-responsive">

                        <table class="table table-bordered align-middle"
                            id="userTable">

                            <thead class="table-light">

                                <tr class="text-center">

                                    <th>
                                        <input type="checkbox"
                                            id="selectAllUser">
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

                                    <tr
                                        data-role="<?= strtolower($row['role']) ?>"
                                        data-status="aktif">

                                        <td class="text-center">
                                            <input type="checkbox"
                                                class="row-checkbox"
                                                value="<?= $row['id_user'] ?>">
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

                                            <button class="btn btn-info btn-sm">
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

                        <small class="text-muted"
                            id="rowCountUser">

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

        <!-- Modal Tambah Akun Staff -->
        <div class="modal fade"
            id="tambahStaffModal"
            tabindex="-1"
            aria-labelledby="tambahStaffModalLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header border-0 pb-0">

                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body pt-0 px-5 pb-4">

                        <div class="text-center mb-4">

                            <h2 class="text-primary fw-bold"
                                id="tambahStaffModalLabel">

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

                        <form action="controllers/UserController.php?action=create_staff"
                            method="POST">

                            <div class="row g-4 mb-4">

                                <div class="col-md-6">

                                    <label class="form-label text-muted fw-semibold">
                                        Nama Staff
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="nama"
                                        placeholder="Roland"
                                        required>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label text-muted fw-semibold">
                                        Email
                                    </label>

                                    <input type="email"
                                        class="form-control"
                                        name="email"
                                        placeholder="rolan@polije.ac.id"
                                        required>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label text-muted fw-semibold">
                                        Penanggung Jawab Ruangan
                                    </label>

                                    <select class="form-select text-muted">

                                        <option selected>
                                            Kelas
                                        </option>

                                        <option value="1">
                                            Laboratorium
                                        </option>

                                        <option value="2">
                                            Ruang Rapat
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label text-muted fw-semibold">
                                        Password
                                    </label>

                                    <input type="password"
                                        class="form-control"
                                        name="password"
                                        placeholder="XXXXXX"
                                        required>

                                </div>

                            </div>

                            <div class="d-flex justify-content-end align-items-center mt-4 pt-3">

                                <button type="button"
                                    class="btn btn-outline-secondary px-4 me-2"
                                    data-bs-dismiss="modal">

                                    Kembali
                                </button>

                                <button type="submit"
                                    class="btn btn-primary px-4">

                                    Kirim
                                </button>

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