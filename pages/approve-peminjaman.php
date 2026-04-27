<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4 fw-bold">Persetujuan Peminjaman</h1>
            <p class="text-muted mb-4">Kelola pengajuan yang menunggu persetujuan. Peradaban dibangun di atas tabel dan tombol kecil.</p>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">

                    <!-- Top Action -->
                    <div class="d-flex justify-content-between align-items-center px-4 pt-4 pb-3">

                        <!-- Search + Filter -->
                        <div class="d-flex align-items-center gap-2">

                            <!-- Filter -->
                            <button class="btn btn-light border rounded-3"
                                style="width:42px;height:42px;">
                                <i class="fas fa-filter text-secondary"></i>
                            </button>

                            <!-- Search -->
                            <div class="position-relative">
                                <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"
                                    style="font-size:13px;"></i>

                                <input type="text"
                                    class="form-control rounded-3 ps-5 border-0 bg-light"
                                    placeholder="Search..."
                                    style="width:280px;height:42px;">
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">

                            <thead style="background:#f8f9fc;">
                                <tr class="text-uppercase text-muted small">
                                    <th class="ps-4 fw-semibold" style="width:50px;">
                                        <input type="checkbox">
                                    </th>
                                    <th class="fw-semibold">No</th>
                                    <th class="fw-semibold">Nama Barang</th>
                                    <th class="fw-semibold">Keterangan</th>
                                    <th class="fw-semibold">Status</th>
                                    <th class="fw-semibold">Waktu Mulai</th>
                                    <th class="fw-semibold">Waktu Selesai</th>
                                    <th class="fw-semibold">Tanggal</th>
                                    <th class="fw-semibold text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                <!-- Row 1 -->
                                <tr>
                                    <td class="ps-4"><input type="checkbox"></td>
                                    <td>1</td>
                                    <td class="fw-semibold">Printer</td>

                                    <td class="text-muted">
                                        Lorem ipsum dolor sit amet,<br>
                                        consectetur adipiscing elit...
                                    </td>

                                    <td>
                                        <span class="badge rounded-pill px-3 py-2"
                                            style="background:#fff4e5;color:#ff9800;">
                                            Pending
                                        </span>
                                    </td>

                                    <td>10.00</td>
                                    <td>12.00</td>

                                    <td class="text-muted">
                                        9 Oktober<br>2025
                                    </td>

                                    <td class="text-center">
                                        <button class="btn btn-sm rounded-3 px-3 me-1"
                                            style="background:#e7f7ff;color:#00a3ff;">
                                            Setujui
                                        </button>

                                        <button class="btn btn-sm rounded-3 px-3"
                                            style="background:#ffe9ea;color:#ff4d4f;">
                                            Tolak
                                        </button>
                                    </td>
                                </tr>

                                <!-- Row 2 -->
                                <tr>
                                    <td class="ps-4"><input type="checkbox"></td>
                                    <td>2</td>
                                    <td class="fw-semibold">Ruang 3.11</td>

                                    <td class="text-muted">
                                        Lorem ipsum dolor sit amet,<br>
                                        consectetur adipiscing elit...
                                    </td>

                                    <td>
                                        <span class="badge rounded-pill px-3 py-2"
                                            style="background:#fff4e5;color:#ff9800;">
                                            Pending
                                        </span>
                                    </td>

                                    <td>15.00</td>
                                    <td>17.00</td>

                                    <td class="text-muted">
                                        6 Oktober<br>2025
                                    </td>

                                    <td class="text-center">
                                        <button class="btn btn-sm rounded-3 px-3 me-1"
                                            style="background:#e7f7ff;color:#00a3ff;">
                                            Setujui
                                        </button>

                                        <button class="btn btn-sm rounded-3 px-3"
                                            style="background:#ffe9ea;color:#ff4d4f;">
                                            Tolak
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">

                        <small class="text-muted">
                            1-10 of 97
                        </small>

                        <div class="d-flex align-items-center gap-2">

                            <small class="text-muted">
                                Rows per page: 10
                            </small>

                            <button class="btn btn-light btn-sm rounded-circle border"
                                style="width:30px;height:30px;">
                                <i class="fas fa-chevron-left small"></i>
                            </button>

                            <small>1/10</small>

                            <button class="btn btn-light btn-sm rounded-circle border"
                                style="width:30px;height:30px;">
                                <i class="fas fa-chevron-right small"></i>
                            </button>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>
</div>