<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <!-- Title -->
            <h1 class="mt-4">Akun</h1>

            <!-- Action -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Daftar akun pengguna</span>
                <a href="#" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Akun
                </a>
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

                                <!-- Row 1 -->
                                <tr>
                                    <td class="text-center"><input type="checkbox"></td>
                                    <td class="text-center">1</td>
                                    <td><strong>Admin Utama</strong></td>
                                    <td>admin@email.com</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary">Admin</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success">Aktif</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm">Detail</button>
                                    </td>
                                </tr>

                                <!-- Row 2 -->
                                <tr>
                                    <td class="text-center"><input type="checkbox"></td>
                                    <td class="text-center">2</td>
                                    <td><strong>Petugas Sarpras</strong></td>
                                    <td>petugas@email.com</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary-subtle text-secondary">Petugas</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success">Aktif</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm">Detail</button>
                                    </td>
                                </tr>

                                <!-- Row 3 -->
                                <tr>
                                    <td class="text-center"><input type="checkbox"></td>
                                    <td class="text-center">3</td>
                                    <td><strong>User Biasa</strong></td>
                                    <td>user@email.com</td>
                                    <td class="text-center">
                                        <span class="badge bg-dark-subtle text-dark">User</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm">Detail</button>
                                    </td>
                                </tr>

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
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</div>