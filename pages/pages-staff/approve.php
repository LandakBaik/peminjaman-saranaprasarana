<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <!-- Title -->
            <h1 class="mt-4">Peminjaman Saya</h1>

            <!-- Action -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Peminjaman anda saat ini</span>
                <a href="#" class="btn btn-primary btn-sm">
                    <i class="fas fa-history me-1"></i> Lihat Riwayat
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
                                    <th>Nama Peminjaman</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                                <!-- Row 1 -->
                                <tr>
                                    <td class="text-center"><input type="checkbox"></td>
                                    <td class="text-center">1</td>
                                    <td>
                                        <strong>Printer</strong><br>
                                        <small class="text-muted">Barang</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit...
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success">Disetujui</span>
                                    </td>
                                    <td class="text-center">10.00</td>
                                    <td class="text-center">12.00</td>
                                    <td class="text-center">
                                        9 Oktober<br><small class="text-muted">2025</small>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm">Kembalikan</button>
                                    </td>
                                </tr>

                                <!-- Row 2 -->
                                <tr>
                                    <td class="text-center"><input type="checkbox"></td>
                                    <td class="text-center">2</td>
                                    <td>
                                        <strong>Ruang 3.11</strong><br>
                                        <small class="text-muted">Ruangan</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit...
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary">Pending</span>
                                    </td>
                                    <td class="text-center">15.00</td>
                                    <td class="text-center">17.00</td>
                                    <td class="text-center">
                                        6 Oktober<br><small class="text-muted">2025</small>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-danger btn-sm">Batalkan</button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">1–2 of 2</small>

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