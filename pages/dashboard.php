<?php
// View variables are provided by DashboardController
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

                    <select id="filterType" class="form-select form-select-sm w-auto"
                        onchange="window.location.href='index.php?page=dashboard&filter=' + this.value">
                        <option value="daily" <?= $activeFilter === 'daily' ? 'selected' : '' ?>>Harian </option>
                        <option value="weekly" <?= $activeFilter === 'weekly' ? 'selected' : '' ?>>Mingguan </option>
                        <option value="monthly" <?= $activeFilter === 'monthly' ? 'selected' : '' ?>>Bulanan </option>
                        <option value="yearly" <?= $activeFilter === 'yearly' ? 'selected' : '' ?>>Tahunan </option>
                    </select>
                </div>
            </div>

            <div class="row g-3 my-4 mt-0">
                <!-- Weather Card -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm text-white"
                        style="background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);">
                        <div class="card-body d-flex align-items-center justify-content-between py-3 px-4">
                            <div class="d-flex align-items-center gap-4">
                                <div id="weatherIcon" class="display-6">
                                    <i class="fas fa-cloud-sun-rain"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold" id="temperatureDisplay">--¡ÆC</h3>
                                    <span id="weatherDescription"
                                        class="badge bg-white bg-opacity-25 text-white fw-normal">Memuat cuaca...</span>
                                </div>
                            </div>
                            <div class="text-end d-none d-md-block">
                                <div class="h5 mb-1 fw-semibold" id="locationName"><i
                                        class="fas fa-location-dot me-2"></i>Jember</div>
                                <div class="small opacity-75" id="currentDateText"><?= date('l, d F Y') ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-1 shadow-sm h-100" style="cursor: pointer;" onclick="showStatsModal('total', 'Semua Peminjaman')">
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
                    <div class="card border-1 shadow-sm h-100" style="cursor: pointer;" onclick="showStatsModal('disetujui', 'Peminjaman Disetujui')">
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
                    <div class="card border-1 shadow-sm h-100" style="cursor: pointer;" onclick="showStatsModal('ditolak', 'Peminjaman Ditolak')">
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
                    <div class="card border-1 shadow-sm h-100" style="cursor: pointer;" onclick="showStatsModal('terlambat', 'Peminjaman Terlambat')">
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

                            <!-- Responsive Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
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

                                                    <td>
                                                        <?= htmlspecialchars($row['keperluan']) ?>
                                                    </td>

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

                                                        <span class="badge <?= $statusClass ?>">
                                                            <?= $row['status'] ?>
                                                        </span>
                                                    </td>

                                                    <td class="small text-nowrap">
                                                        <?= date('d/m/Y H:i', strtotime($row['waktu_mulai'])) ?>
                                                        -
                                                        <?= date('d/m/Y H:i', strtotime($row['waktu_selesai'])) ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>

                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    Belum ada riwayat peminjaman.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

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

    <!-- Modal Detail Stats -->
    <div class="modal fade" id="statsDetailModal" tabindex="-1" aria-labelledby="statsDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 bg-light py-3">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="statsDetailModalLabel">Detail Peminjaman</h5>
                        <small class="text-muted" id="statsDetailModalSubtitle">Menampilkan daftar peminjaman</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="table-responsive" id="modalTableContainer">
                        <!-- Dynamic table will be inserted here -->
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2">
                    <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

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

    let statsDataTable = null;

    // Reset modal contents and destroy DataTable instance when modal is closed
    document.addEventListener('DOMContentLoaded', () => {
        const modalElement = document.getElementById('statsDetailModal');
        if (modalElement) {
            modalElement.addEventListener('hidden.bs.modal', () => {
                if (statsDataTable) {
                    statsDataTable.destroy();
                    statsDataTable = null;
                }
                const container = document.getElementById('modalTableContainer');
                if (container) {
                    container.innerHTML = '';
                }
            });
        }
    });

    function showStatsModal(statusType, title) {
        // 1. Set title and subtitle
        document.getElementById('statsDetailModalLabel').innerText = 'Detail Peminjaman: ' + title;
        
        const activeFilter = '<?= $activeFilter ?>';
        const filterLabels = {
            'daily': 'Hari Ini',
            'weekly': '7 Hari Terakhir',
            'monthly': '30 Hari Terakhir',
            'yearly': 'Tahun Ini'
        };
        document.getElementById('statsDetailModalSubtitle').innerText = `Menampilkan daftar peminjaman (${filterLabels[activeFilter] || activeFilter})`;
        
        // 2. Show loading spinner
        const container = document.getElementById('modalTableContainer');
        container.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2 mb-0">Memuat data peminjaman...</p>
            </div>
        `;
        
        // 3. Show the modal
        const modalElement = document.getElementById('statsDetailModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        modal.show();
        
        // 4. Fetch the data
        fetch(`index.php?page=dashboard-stats-details&status=${statusType}&filter=${activeFilter}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text();
            })
            .then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error("Malformed JSON response:", text);
                    throw new Error("Respon server bukan format JSON yang valid. Silakan hubungi admin.");
                }
            })
            .then(data => {
                if (statsDataTable) {
                    statsDataTable.destroy();
                    statsDataTable = null;
                }
                
                if (!data || data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-50"></i>
                            <p class="text-muted mb-0">Tidak ada data peminjaman untuk kategori ini.</p>
                        </div>
                    `;
                    return;
                }
                
                // Clear the container
                container.innerHTML = '';
                
                // Create a completely brand new table element to guarantee zero DOM recycling collisions
                const freshTable = document.createElement('table');
                freshTable.id = 'modalDatatable';
                freshTable.className = 'table table-bordered table-striped align-middle';
                
                // Build the table markup
                let tableInnerHtml = `
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Kode</th>
                            <th>Peminjam</th>
                            <th>Nama Peminjaman</th>
                            <th>Keperluan</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                `;
                
                data.forEach((row, index) => {
                    // Formatting Kode PJM
                    const dateObj = new Date(row.tanggal_dibuat);
                    const day = String(dateObj.getDate()).padStart(2, '0');
                    const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                    const year = dateObj.getFullYear();
                    const kode = `PJM-${day}${month}${year}-${row.id_peminjaman}`;
                    
                    // Formatting Nama Peminjaman
                    let namaPeminjaman = '';
                    if (row.jenis_peminjaman === 'ruangan') {
                        namaPeminjaman = `<i class="fas fa-door-open text-primary me-2"></i> ${row.nama_ruangan || '(Tanpa Nama)'}`;
                    } else {
                        namaPeminjaman = `<i class="fas fa-boxes text-success me-2"></i> ${row.items || '(Tanpa Nama)'}`;
                    }
                    
                    // Format times
                    const waktuMulai = formatDateTime(row.waktu_mulai);
                    const waktuSelesai = formatDateTime(row.waktu_selesai);
                    
                    // Badge Class
                    let badgeClass = 'bg-secondary';
                    const status = row.status;
                    if (status === 'Pending') {
                        badgeClass = 'bg-warning text-dark';
                    } else if (status === 'Disetujui' || status === 'Selesai' || status === 'Approved') {
                        badgeClass = 'bg-success';
                    } else if (status === 'Ditolak' || status === 'Dibatalkan' || status === 'Rejected') {
                        badgeClass = 'bg-danger';
                    } else if (status === 'Dipinjam') {
                        badgeClass = 'bg-primary';
                    } else if (status === 'Terlambat') {
                        badgeClass = 'bg-danger';
                    }
                    
                    tableInnerHtml += `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td class="fw-semibold">${kode}</td>
                            <td>${row.peminjam || '-'}</td>
                            <td>${namaPeminjaman}</td>
                            <td><small class="text-muted">${row.keperluan || '-'}</small></td>
                            <td class="small text-nowrap">${waktuMulai}</td>
                            <td class="small text-nowrap">${waktuSelesai}</td>
                            <td class="text-center">
                                <span class="badge ${badgeClass}">${status}</span>
                            </td>
                        </tr>
                    `;
                });
                
                tableInnerHtml += `
                    </tbody>
                `;
                
                freshTable.innerHTML = tableInnerHtml;
                container.appendChild(freshTable);
                
                // Initialize after a tiny delay so that the DOM settles and Bootstrap transition is done
                setTimeout(() => {
                    statsDataTable = new simpleDatatables.DataTable(freshTable, {
                        sortable: false,
                        labels: {
                            placeholder: "Cari...",
                            perPage: "{select} data per halaman",
                            noRows: "Tidak ada data ditemukan",
                            info: "Menampilkan {start} sampai {end} dari {rows} data",
                        }
                    });
                }, 50);
            })
            .catch(err => {
                console.error("Fetch error:", err);
                container.innerHTML = `
                    <div class="alert alert-danger mb-0" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> 
                        <strong>Gagal memuat data peminjaman.</strong>
                        <p class="small text-muted mt-2 mb-0">${err.message}</p>
                    </div>
                `;
            });
    }

    function formatDateTime(dateTimeStr) {
        if (!dateTimeStr) return '-';
        const date = new Date(dateTimeStr);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${day}/${month}/${year} ${hours}:${minutes}`;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="assets/demo/chart-line.js"></script>
<script src="assets/demo/chart-doughnut.js"></script>
<script src="assets/demo/kpi.js"></script>
<script src="assets/demo/dashboard.js"></script>