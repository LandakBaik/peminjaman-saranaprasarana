<?php
// Ensure session and role are available
$currentRole = isset($_SESSION['user']['role']) ? strtolower($_SESSION['user']['role']) : '';
$userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;

// Filter logic
$activeFilter = $_GET['filter'] ?? 'daily';
$allowedFilters = ['daily', 'weekly', 'monthly','yearly'];
if (!in_array($activeFilter, $allowedFilters))
    $activeFilter = 'daily';

$database = new \App\Config\Database();
$db = $database->getConnection();
$peminjamanModel = new \App\Models\Peminjaman($db);

// Fetch Stats based on role
$stats = $peminjamanModel->getStats($currentRole, $userId, $activeFilter);

// Fetch Recent Peminjaman based on role
$recent = $peminjamanModel->getRecent($currentRole, $userId, 5, $activeFilter);

// Fetch Trend Data for Line Chart
$trendData = $peminjamanModel->getTrendData($currentRole, $userId, $activeFilter);
?>

<div id="layoutSidenav_content">
    <main id="dashboard">
        <div class="container-fluid px-4">

            <div class="d-flex justify-content-between align-items-center mt-4 mb-0">
                <div>
                    <h1 class="mb-3">Dashboard</h1>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active">
                            <?php
                            if ($currentRole === 'user')
                                echo "Ringkasan Aktivitas Saya";
                            elseif ($currentRole === 'admin')
                                echo "Statistik Sistem (Admin)";
                            elseif ($currentRole === 'staff')
                                echo "Statistik Ruangan (Staff)";
                            else
                                echo "Dashboard Overview";
                            ?>
                        </li>
                    </ol>
                </div>

                <div class="d-flex gap-2 align-items-center">
                    <div id="customDateInputs" class="d-none d-flex gap-2">
                        <input type="date" id="dateStart" class="form-control form-control-sm" style="width: 140px;">
                        <span class="text-muted small align-self-center">s/d</span>
                        <input type="date" id="dateEnd" class="form-control form-control-sm" style="width: 140px;">
                    </div>

                    <select id="filterType" class="form-select form-select-sm w-auto" onchange="window.location.href='index.php?page=dashboard&filter=' + this.value">
                        <option value="daily" <?= $activeFilter === 'daily' ? 'selected' : '' ?>>Harian </option>
                        <option value="weekly" <?= $activeFilter === 'weekly' ? 'selected' : '' ?>>Mingguan </option>
                        <option value="monthly" <?= $activeFilter === 'monthly' ? 'selected' : '' ?>>Bulanan </option>
                        <option value="yearly" <?= $activeFilter === 'yearly' ? 'selected' : '' ?>>Tahunan </option>
                    </select>
                </div>
            </div>

            <div class="row g-3 my-4 mt-0">
                <!-- Total -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-1 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary"
                                style="width:50px; height:50px;">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold" id="totalPinjam"><?= $stats['total'] ?></h4>
                                <small class="text-muted">Total Peminjaman</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disetujui -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-1 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success"
                                style="width:50px; height:50px;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold" id="disetujui"><?= $stats['disetujui'] ?></h4>
                                <small class="text-muted">Peminjaman Disetujui</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-1 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger"
                                style="width:50px; height:50px;">
                                <i class="fas fa-times"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold" id="ditolak"><?= $stats['ditolak'] ?></h4>
                                <small class="text-muted">Peminjaman Ditolak</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terlambat -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-1 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning"
                                style="width:50px; height:50px;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold" id="terlambat"><?= $stats['terlambat'] ?></h4>
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
                            Tren Peminjaman
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
                            <?= ($currentRole === 'user') ? 'Peminjaman Terkini Saya' : 'Data Peminjaman Terkini' ?>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Peminjaman</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th>Waktu Peminjaman</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($recent && $recent->rowCount() > 0): ?>
                                            <?php while ($row = $recent->fetch(PDO::FETCH_ASSOC)): ?>
                                                    <tr>
                                                        <td class="fw-semibold">
                                                            <?php
                                                            $namaPeminjaman = '';
                                                            if ($row['jenis_peminjaman'] === 'ruangan') {
                                                                $namaPeminjaman = "Pinjam Ruangan: " . ($row['nama_ruangan'] ?? '(Tanpa Nama/Item)');
                                                            } else {
                                                                $namaPeminjaman = "Pinjam Barang: " . ($row['items'] ?? '(Tanpa Nama)');
                                                            }
                                                            echo htmlspecialchars((string) $namaPeminjaman);
                                                            ?>
                                                        </td>
                                                        <td><?= htmlspecialchars($row['keperluan']) ?></td>
                                                        <td>
                                                            <?php
                                                            $statusClass = 'bg-secondary';
                                                            if ($row['status'] == 'Pending')
                                                                $statusClass = 'bg-warning text-dark';
                                                            elseif ($row['status'] == 'Disetujui' || $row['status'] == 'Selesai')
                                                                $statusClass = 'bg-success';
                                                            elseif ($row['status'] == 'Ditolak' || $row['status'] == 'Dibatalkan')
                                                                $statusClass = 'bg-danger';
                                                            elseif ($row['status'] == 'Dipinjam')
                                                                $statusClass = 'bg-primary';
                                                            elseif ($row['status'] == 'Terlambat')
                                                                $statusClass = 'bg-danger';
                                                            ?>
                                                            <span class="badge <?= $statusClass ?>"><?= $row['status'] ?></span>
                                                        </td>
                                                        <td class="small">
                                                            <?= date('d/m/Y', strtotime($row['waktu_mulai'])) ?> - <?= date('d/m/Y', strtotime($row['waktu_selesai'])) ?>
                                                        </td>
                                                    </tr>
                                            <?php endwhile; ?>
                                    <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat peminjaman.</td>
                                            </tr>
                                    <?php endif; ?>
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

<script>
    // Pass real data to dashboard.js
    window.dashboardStats = {
        total: <?= $stats['total'] ?>,
        disetujui: <?= $stats['disetujui'] ?>,
        ditolak: <?= $stats['ditolak'] ?>,
        terlambat: <?= $stats['terlambat'] ?>,
        trend: <?= json_encode($trendData) ?>
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="assets/demo/chart-line.js"></script>
<script src="assets/demo/chart-doughnut.js"></script>
<script src="assets/demo/kpi.js"></script>
<script src="assets/demo/dashboard.js"></script>