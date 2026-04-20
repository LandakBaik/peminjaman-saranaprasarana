<div id="layoutSidenav_content">
    <main id="dashboard">
        <div class="container-fluid px-4">

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <div>
                    <h1 class="m-0">Dashboard</h1>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active">Dashboard Overview (Mode Dummy)</li>
                    </ol>
                </div>

                <div class="d-flex gap-2 align-items-center">
                    <div id="customDateInputs" class="d-none d-flex gap-2">
                        <input type="date" id="dateStart" class="form-control form-control-sm" style="width: 140px;">
                        <span class="text-muted small align-self-center">s/d</span>
                        <input type="date" id="dateEnd" class="form-control form-control-sm" style="width: 140px;">
                    </div>

                    <select id="filterType" class="form-select form-select-sm w-auto">
                        <option value="daily" selected>Harian </option>
                        <option value="weekly">Mingguan </option>
                        <option value="monthly">Bulanan </option>
                    </select>
                </div>
            </div>

            <div class="row g-3 my-5">
                <!-- Total -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary"
                                style="width:50px; height:50px;">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold" id="totalPinjam">0</h4>
                                <small class="text-muted">Total Peminjaman</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disetujui -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success"
                                style="width:50px; height:50px;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold" id="disetujui">0</h4>
                                <small class="text-muted">Peminjaman Disetujui</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger"
                                style="width:50px; height:50px;">
                                <i class="fas fa-times"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold" id="ditolak">0</h4>
                                <small class="text-muted">Peminjaman Ditolak</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terlambat -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning"
                                style="width:50px; height:50px;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold" id="terlambat">0</h4>
                                <small class="text-muted">Peminjaman Terlambat</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-area me-1"></i>
                            Tren Aktivitas Penjualan
                        </div>
                        <div class="card-body">
                            <canvas id="myAreaChart" width="100%" height="30"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- TABLE (lebih lebar) -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Karyawan Terkini
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Tiger Nixon</td>
                                        <td>System Architect</td>
                                        <td>Edinburgh</td>
                                        <td>61</td>
                                        <td>2011/04/25</td>
                                        <td>$320,800</td>
                                    </tr>
                                    <!-- dst -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- DOUGHNUT CHART -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-pie me-1"></i>
                            Statistik Peminjaman
                        </div>
                        <div class="card-body">
                            <canvas id="myDoughnutChart" height="200"></canvas>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="assets/demo/chart-line.js"></script>
<script src="assets/demo/chart-doughnut.js"></script>
<script src="assets/demo/kpi.js"></script>
<script src="assets/demo/dashboard.js"></script>