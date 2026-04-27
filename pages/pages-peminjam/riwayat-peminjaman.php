<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <!-- Title -->
            <h1 class="mt-4 fw-bold">Riwayat Peminjaman</h1>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted"> Daftar peminjaman yang telah selesai, ditolak, atau dibatalkan</span>
                <a href="index.php?page=peminjaman-saya" class="btn btn-primary btn-sm">Kembali</a>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Search + Filter -->
                    <div class="d-flex align-items-center mb-3">
                        <!-- Tombol Filter -->
                        <button type="button" class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px;" data-bs-toggle="modal" data-bs-target="#filterModal" id="btnFilterToggle" title="Filter">
                            <i class="fas fa-filter" style="font-size: 16px;"></i>
                        </button>

                        <!-- Search Bar -->
                        <input type="text" class="form-control" style="max-width: 250px; height: 42px;" placeholder="Search..." id="searchInput">
                    </div>

                    <!-- popup filter -->
                    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
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
                                            <option value="Pending">Selesai</option>
                                            <option value="Disetujui">Ditolak</option>
                                            <option value="Ditolak">Dibatalkan</option>
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
                                        <label class="form-label fw-semibold">Tanggal</label>
                                        <input type="date" class="form-control" id="filterTanggal">
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary" id="btnResetFilter">Reset</button>
                                    <button type="button" class="btn btn-primary" id="btnApplyFilter" data-bs-dismiss="modal">Terapkan</button>
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
                                    <th>Nama Peminjaman</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="peminjamanBody">

                                <!-- Row 1 -->
                                <tr data-status="Disetujui" data-type="Barang">
                                    <td class="text-center"><input type="checkbox" class="row-checkbox"></td>
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
                                        <span class="badge bg-success-subtle text-success">Selesai</span>
                                    </td>
                                    <td class="text-center">10.00</td>
                                    <td class="text-center">12.00</td>
                                    <td class="text-center">
                                        9 Oktober<br><small class="text-muted">2025</small>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary">Print</button> 
                                    </td>
                                </tr>

                                <!-- Row 2 -->
                                <tr data-status="Pending" data-type="Ruangan">
                                    <td class="text-center"><input type="checkbox" class="row-checkbox"></td>
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
                                        <span class="badge bg-primary-subtle text-primary">Ditolak</span>
                                    </td>
                                    <td class="text-center">15.00</td>
                                    <td class="text-center">17.00</td>
                                    <td class="text-center">
                                        6 Oktober<br><small class="text-muted">2025</small>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary">Print</button> 
                                    </td>
                                </tr>

                                <!-- Row 3 -->
                                <tr data-status="Pending" data-type="Ruangan">
                                    <td class="text-center"><input type="checkbox" class="row-checkbox"></td>
                                    <td class="text-center">2</td>
                                    <td>
                                        <strong>Ruang 3.12</strong><br>
                                        <small class="text-muted">Ruangan</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit...
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary">Dibatalkan</span>
                                    </td>
                                    <td class="text-center">15.00</td>
                                    <td class="text-center">17.00</td>
                                    <td class="text-center">
                                        6 Oktober<br><small class="text-muted">2025</small>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary">Print</button> 
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted" id="rowCount">1–2 of 2</small>

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
</div> 
<!-- Script Filter -->
<script src="js/filter.js"></script>
